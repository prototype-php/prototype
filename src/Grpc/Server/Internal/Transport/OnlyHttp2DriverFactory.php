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

namespace Prototype\Grpc\Server\Internal\Transport;

use Amp\Http\Server\Driver\Client;
use Amp\Http\Server\Driver\Http2Driver;
use Amp\Http\Server\Driver\HttpDriver;
use Amp\Http\Server\Driver\HttpDriverFactory;
use Amp\Http\Server\ErrorHandler;
use Amp\Http\Server\RequestHandler;
use Psr\Log\LoggerInterface as PsrLogger;
use Psr\Log\NullLogger;

/**
 * @internal
 * @psalm-internal Prototype\Grpc
 */
final class OnlyHttp2DriverFactory implements HttpDriverFactory
{
    public function __construct(
        private readonly PsrLogger $logger = new NullLogger(),
        private readonly int $streamTimeout = HttpDriver::DEFAULT_STREAM_TIMEOUT,
        private readonly int $connectionTimeout = HttpDriver::DEFAULT_CONNECTION_TIMEOUT,
        private readonly int $headerSizeLimit = HttpDriver::DEFAULT_HEADER_SIZE_LIMIT,
        private readonly int $bodySizeLimit = HttpDriver::DEFAULT_BODY_SIZE_LIMIT,
    ) {}

    public function createHttpDriver(RequestHandler $requestHandler, ErrorHandler $errorHandler, Client $client): HttpDriver
    {
        return new Http2Driver(
            requestHandler: $requestHandler,
            errorHandler: $errorHandler,
            logger: $this->logger,
            streamTimeout: $this->streamTimeout,
            connectionTimeout: $this->connectionTimeout,
            headerSizeLimit: $this->headerSizeLimit,
            bodySizeLimit: $this->bodySizeLimit,
            pushEnabled: true,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getApplicationLayerProtocols(): array
    {
        return ['h2'];
    }
}
