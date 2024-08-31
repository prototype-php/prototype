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

namespace Prototype\Grpc\Server\Internal\Adapter;

use Amp\CancelledException;
use Amp\Http\Server\Request;
use Amp\Http\Server\RequestHandler;
use Amp\Http\Server\Response;
use Amp\TimeoutException;
use Prototype\Grpc\Server\Internal\Cancellation\CancellationFactory;
use Prototype\Grpc\Server\Internal\Exception\ServerException;
use Prototype\Grpc\Server\Internal\Io\GrpcRequest;
use Prototype\Grpc\Server\Internal\Io\GrpcResponse;
use Prototype\Grpc\Server\MethodNotImplemented;
use Prototype\Grpc\StatusCode;

/**
 * @internal
 * @psalm-internal Prototype\Grpc
 */
final class ServerRequestHandler implements RequestHandler
{
    /**
     * @param array<non-empty-string, non-empty-string> $headers
     */
    public function __construct(
        private readonly GrpcRequestHandler $requestHandler,
        private readonly CancellationFactory $cancellations,
        private readonly array $headers = [],
    ) {}

    public function handleRequest(Request $request): Response
    {
        try {
            $grpcRequest = GrpcRequest::fromServerRequest($request);

            $response = $this->requestHandler
                ->handle(
                    $grpcRequest,
                    $this->cancellations->createCancellation($grpcRequest->timeout),
                );
        } catch (ServerException $e) {
            $response = GrpcResponse::error($e->status, $e->errorMessage)
                ->withHttpStatus($e->httpStatus)
            ;
        } catch (MethodNotImplemented $e) {
            $response = GrpcResponse::error(StatusCode::UNIMPLEMENTED, $e->getMessage());
        } catch (CancelledException | TimeoutException) {
            $response = GrpcResponse::error(StatusCode::DEADLINE_EXCEEDED);
        } catch (\Throwable) {
            $response = GrpcResponse::error();
        }

        return $response
            ->withHeaders($this->headers)
            ->toServerResponse()
            ;
    }
}
