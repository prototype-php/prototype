<?php

/**
 * MIT License
 * Copyright (c) 2024 kafkiansky.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

declare(strict_types=1);

namespace Prototype\Grpc\Server;

use Amp\Http\Server\SocketHttpServer;
use Amp\Socket\BindContext;
use Amp\Socket\SocketAddress;
use Prototype\Grpc\Compression\Compressor;
use Prototype\Grpc\Compression\IdentityCompressor;
use Prototype\Grpc\Server\Internal\Cancellation\CancellationFactory;
use Prototype\Grpc\Server\Internal\Handler\InterceptedGrpcRequestHandler;
use Prototype\Grpc\Server\Internal\Handler\MessageDispatcher;
use Prototype\Grpc\Server\Internal\Handler\MessageCompressor;
use Prototype\Grpc\Server\Internal\Transport\OnlyHttp2DriverFactory;
use Prototype\Grpc\Timeout;
use Prototype\Serializer\Serializer;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * @api
 */
final class ServerBuilder implements ServiceRegistry
{
    private ?LoggerInterface $logger = null;

    /** @var list<SocketAddress|non-empty-string> */
    private array $addresses = [];

    private ?BindContext $bindContext = null;

    /** @var positive-int */
    private int $connectionLimit = 1000;

    /** @var ?positive-int */
    private ?int $concurrencyLimit = 1000;

    /** @var positive-int */
    private int $connectionLimitPerIp = 10;

    /** @var ?float */
    private ?float $requestTimeout = null;

    private ?ProxyOptions $proxy = null;

    private ?Serializer $serializer = null;

    /** @var array<non-empty-string, array<non-empty-string, RpcMethod>> */
    private array $services = [];

    /** @var list<Compressor> */
    private array $compressors = [];

    public function withLogger(LoggerInterface $logger): self
    {
        $builder = clone $this;
        $builder->logger = $logger;

        return $builder;
    }

    /**
     * @param SocketAddress|non-empty-string $address
     */
    public function withAddress(SocketAddress|string $address): self
    {
        $builder = clone $this;
        $builder->addresses[] = $address;

        return $builder;
    }

    public function withBindContext(BindContext $bindContext): self
    {
        $builder = clone $this;
        $builder->bindContext = $bindContext;

        return $builder;
    }

    /**
     * @param positive-int $connectionLimit
     */
    public function withConnectionLimit(int $connectionLimit): self
    {
        $builder = clone $this;
        $builder->connectionLimit = $connectionLimit;

        return $builder;
    }

    /**
     * @param positive-int $connectionLimitPerIp
     */
    public function withConnectionLimitPerIp(int $connectionLimitPerIp): self
    {
        $builder = clone $this;
        $builder->connectionLimitPerIp = $connectionLimitPerIp;

        return $builder;
    }

    /**
     * @param positive-int $concurrencyLimit
     */
    public function withConcurrencyLimit(int $concurrencyLimit): self
    {
        $builder = clone $this;
        $builder->concurrencyLimit = $concurrencyLimit;

        return $builder;
    }

    public function withoutConcurrencyLimit(): self
    {
        $builder = clone $this;
        $builder->concurrencyLimit = null;

        return $builder;
    }

    public function withProxy(ProxyOptions $proxy): self
    {
        $builder = clone $this;
        $builder->proxy = $proxy;

        return $builder;
    }

    public function withSerializer(Serializer $serializer): self
    {
        $builder = clone $this;
        $builder->serializer = $serializer;

        return $builder;
    }

    public function withCompressor(Compressor $compressor): self
    {
        $builder = clone $this;
        $builder->compressors[] = $compressor;

        return $builder;
    }

    public function withRequestTimeout(Timeout $timeout): self
    {
        $builder = clone $this;
        $builder->requestTimeout = $timeout->toSeconds();

        return $builder;
    }

    public function addService(ServiceDescriptor $descriptor): static
    {
        if (isset($this->services[$descriptor->name])) {
            throw new \LogicException(\sprintf('Service "%s" is already registered.', $descriptor->name));
        }

        $builder = clone $this;
        $builder->services[$descriptor->name] = array_merge(
            ...array_map(
                static fn (RpcMethod $method): array => [$method->name => $method],
                $descriptor->unaryRpcMethods,
            ),
        );

        return $builder;
    }

    public function registerFromService(ServiceRegistrar $registrar): self
    {
        return $registrar->register($this);
    }

    /**
     * @throws \Amp\Socket\SocketException
     */
    public static function buildDefault(ServiceRegistrar ...$registrars): Server
    {
        $builder = new self();

        foreach ($registrars as $registrar) {
            $builder = $registrar->register($builder);
        }

        return $builder->build();
    }

    /**
     * @throws \Amp\Socket\SocketException
     */
    public function build(): Server
    {
        $logger = $this->logger ?: new NullLogger();

        $socketServer = match (true) {
            null !== $this->proxy => SocketHttpServer::createForBehindProxy(
                logger: $logger,
                headerType: $this->proxy->headerType,
                trustedProxies: $this->proxy->trustedProxies,
                concurrencyLimit: $this->concurrencyLimit,
                httpDriverFactory: new OnlyHttp2DriverFactory(),
            ),
            default => SocketHttpServer::createForDirectAccess(
                logger: $logger,
                connectionLimit: $this->connectionLimit,
                connectionLimitPerIp: $this->connectionLimitPerIp,
                concurrencyLimit: $this->concurrencyLimit,
                httpDriverFactory: new OnlyHttp2DriverFactory(),
            ),
        };

        foreach ($this->addresses ?: ['0.0.0.0:5000'] as $address) {
            $socketServer->expose($address, $this->bindContext);
        }

        $compressors = array_merge(
            ...array_map(
                static fn (Compressor $compressor): array => [$compressor->name() => $compressor],
                [...$this->compressors, ...[new IdentityCompressor()]],
            ),
        );

        return new Server(
            $socketServer,
            new InterceptedGrpcRequestHandler(
                new MessageDispatcher(
                    $this->services,
                    $this->serializer ?: new Serializer(),
                ),
                new MessageCompressor($compressors),
            ),
            new CancellationFactory($this->requestTimeout),
            [
                'grpc-accept-encoding' => implode(',', array_keys($compressors)),
            ],
        );
    }
}
