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
use Prototype\Grpc\Internal\Protocol;
use Prototype\Grpc\Server\Internal\Adapter\GrpcRequestHandler;
use Prototype\Grpc\Server\Internal\Exception\ServerException;
use Prototype\Grpc\Server\Internal\Io;
use Prototype\Grpc\Server\Internal\Io\GrpcResponse;
use Prototype\Grpc\StatusCode;

/**
 * @internal
 * @psalm-internal Prototype\Grpc
 */
final class InterceptedGrpcRequestHandler implements GrpcRequestHandler
{
    private readonly Protocol\Codec $codec;

    public function __construct(
        private readonly MessageDispatcher $dispatcher,
        private readonly MessageCompressor $compressor,
    ) {
        $this->codec = new Protocol\Codec();
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Io\GrpcRequest $request, Cancellation $cancellation = new NullCancellation()): Io\GrpcResponse
    {
        $requestBody = $request->body->buffer($cancellation);

        if ('' === $requestBody) {
            return GrpcResponse::error(StatusCode::INVALID_ARGUMENT);
        }

        $requestFrame = $this->codec
            ->extend($requestBody)
            ->readFrame()
        ;

        $encoding = $requestFrame->compressed
            ? ($request->encoding ?: throw new ServerException(StatusCode::FAILED_PRECONDITION, 'Received compressed frame without an grpc-encoding header.'))
            : 'identity'
        ;

        $responseBody = $this->dispatcher
            ->dispatch(
                $request->endpoint,
                new Message(
                    match (true) {
                        $requestFrame->compressed && '' !== $requestFrame->payload => $this->compressor->decompress($requestFrame->payload, $encoding),
                        default => $requestFrame->payload,
                    },
                ),
                $cancellation,
            )
            ->body ?: ''
        ;

        $responseBody = match (true) {
            $requestFrame->compressed && '' !== $responseBody => $this->compressor->compress($responseBody, $encoding),
            default => $responseBody,
        };

        return GrpcResponse::ok(
            $this->codec
                ->writeFrame(new Protocol\Frame($responseBody, $requestFrame->compressed))
                ->buffer(),
            encoding: $encoding,
        );
    }
}
