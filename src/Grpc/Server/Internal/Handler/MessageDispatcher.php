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

namespace Prototype\Grpc\Server\Internal\Handler;

use Amp\Cancellation;
use Amp\NullCancellation;
use Prototype\Byte\Buffer;
use Prototype\Grpc\Internal\Net\Endpoint;
use Prototype\Grpc\Server\Internal\Exception\ServerException;
use Prototype\Grpc\Server\RpcMethod;
use Prototype\Serializer\Serializer;

/**
 * @internal
 * @psalm-internal Prototype\Grpc
 */
final class MessageDispatcher
{
    /**
     * @param array<non-empty-string, array<non-empty-string, RpcMethod>> $services
     */
    public function __construct(
        private readonly array $services,
        private readonly Serializer $serializer = new Serializer(),
    ) {}

    public function dispatch(
        Endpoint $endpoint,
        Message $message,
        Cancellation $cancellation = new NullCancellation(),
    ): Message {
        $rpc = $this->services[$endpoint->serviceName][$endpoint->rpc] ?? throw new ServerException(errorMessage: \sprintf('No handler found for endpoint "%s".', $endpoint->path));

        $out = ($rpc->handler)(
            fn (string $requestType): object => $this->serializer->deserialize(
                Buffer::fromString($message->body ?: ''),
                $requestType,
            ),
            $cancellation,
        );

        return new Message($this->serializer->serialize($out)->reset());
    }
}
