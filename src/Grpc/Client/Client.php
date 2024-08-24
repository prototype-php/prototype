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

namespace Prototype\Grpc\Client;

use Amp\Cancellation;
use Amp\Http\Client\DelegateHttpClient;
use Amp\NullCancellation;
use Prototype\Grpc\Client\Internal\Wire\RequestFactory;
use Prototype\Grpc\Client\Internal\Wire\ResponseFactory;

/**
 * @api
 */
final class Client
{
    /**
     * @internal
     * @psalm-internal Prototype\Grpc
     */
    public function __construct(
        private readonly ClientOptions $options,
        private readonly DelegateHttpClient $httpClient,
        private readonly RequestFactory $requestFactory,
        private readonly ResponseFactory $responseFactory,
    ) {}

    /**
     * @template T of object
     * @param GrpcRequest<T> $grpcRequest
     * @return GrpcResponse<T>
     * @throws \Throwable
     */
    public function invoke(GrpcRequest $grpcRequest, Cancellation $cancellation = new NullCancellation()): GrpcResponse
    {
        $request = $this->requestFactory->newRequest(
            $grpcRequest->payload,
            \sprintf('%s/%s', $this->options->uri, ltrim($grpcRequest->path, '/')),
        );

        $response = $this->httpClient->request($request, $cancellation);

        /** @var GrpcResponse<T> */
        return $this->responseFactory->fromHTTPResponse(
            $response,
            $grpcRequest->responseType,
            $cancellation,
        );
    }
}
