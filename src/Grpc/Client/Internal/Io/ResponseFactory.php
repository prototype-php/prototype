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

namespace Prototype\Grpc\Client\Internal\Io;

use Amp\Cancellation;
use Amp\Http\Client\Response;
use Amp\NullCancellation;
use Prototype\Byte;
use Prototype\Grpc\Client\GrpcResponse;
use Prototype\Grpc\Compression\Compressor;
use Prototype\Grpc\Internal\Protocol;
use Prototype\Grpc\StatusCode;
use Prototype\Serializer\Serializer;

/**
 * @internal
 * @psalm-internal Prototype\Grpc\Client
 */
final class ResponseFactory
{
    public function __construct(
        private readonly Serializer $serializer,
        private readonly Compressor $compressor,
        private readonly Protocol\Codec $codec = new Protocol\Codec(),
    ) {}

    /**
     * @template T of object
     * @param class-string<T> $messageType
     * @return GrpcResponse<T>
     */
    public function fromHTTPResponse(Response $response, string $messageType, Cancellation $cancellation = new NullCancellation()): GrpcResponse
    {
        /** @var ?numeric-string $grpcStatus */
        $grpcStatus = $response->getHeader('grpc-status');
        $grpcMessage = $response->getHeader('grpc-message');
        $headers = $response->getHeaders();

        if (null === $grpcStatus) {
            $trailers = $response->getTrailers()->await($cancellation);
            $headers += $trailers->getHeaders();

            /** @var ?numeric-string $grpcStatus */
            $grpcStatus = $trailers->getHeader('grpc-status');
            $grpcMessage = $trailers->getHeader('grpc-message');
        }

        $statusCode = null !== $grpcStatus ? (StatusCode::tryFrom((int)$grpcStatus) ?: StatusCode::UNKNOWN) : StatusCode::UNKNOWN;

        if ($statusCode !== StatusCode::OK) {
            return GrpcResponse::error(
                $statusCode,
                $headers,
                $grpcMessage,
            );
        }

        $frame = $this->codec
            ->extend($response->getBody()->buffer($cancellation))
            ->readFrame()
        ;

        $body = $frame->payload;

        if ($frame->compressed && '' !== $body) {
            $body = $this->compressor->decompress($body);
        }

        /** @var T $message */
        $message = $this->serializer->deserialize(
            Byte\Buffer::fromString($body),
            $messageType,
        );

        return GrpcResponse::ok(StatusCode::OK, $message, $response->getHeaders());
    }
}
