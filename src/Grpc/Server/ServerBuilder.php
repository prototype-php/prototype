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

use Amp\Http\Server\RequestHandler;
use Amp\Http\Server\SocketHttpServer;
use Amp\Socket\BindContext;
use Amp\Socket\SocketAddress;
use Prototype\Grpc\Server\Internal\Http\OnlyHttp2DriverFactory;
use Prototype\Grpc\Server\Internal\Http\UnimplementedRequestHandler;
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

    private ?ProxyOptions $proxy = null;

    private ?RequestHandler $requestHandler = null;

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
        $builder->addresses = array_merge($builder->addresses, [$address]);

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

    public function withRequestHandler(RequestHandler $requestHandler): self
    {
        $builder = clone $this;
        $builder->requestHandler = $requestHandler;

        return $builder;
    }

    public function addService(ServiceDescriptor $descriptor): void
    {
        //
    }

    public function registerFromService(ServiceRegistrar $registrar): void
    {
        $registrar->register($this);
    }

    /**
     * @throws \Amp\Socket\SocketException
     */
    public static function buildDefault(): Server
    {
        return (new self())->build();
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

        return new Server(
            $socketServer,
            $this->requestHandler ?: new UnimplementedRequestHandler(),
        );
    }
}
