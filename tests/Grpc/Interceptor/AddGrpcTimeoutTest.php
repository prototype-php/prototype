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

namespace Prototype\Tests\Grpc\Interceptor;

use Amp\Cancellation;
use Amp\NullCancellation;
use PHPUnit\Framework\Attributes\CoversClass;
use Prototype\Grpc\Client\ClientBuilder;
use Prototype\Grpc\Client\ClientOptions;
use Prototype\Grpc\Interceptor\AddGrpcTimeout;
use Prototype\Grpc\Server\ServerBuilder;
use Prototype\Grpc\Timeout;
use Prototype\Tests\Grpc\GrpcTestCase;
use Test\Api\V1\AddTaskRequest;
use Test\Api\V1\AddTaskResponse;
use Test\Api\V1\TestControllerClient;
use Test\Api\V1\TestControllerServer;
use Test\Api\V1\TestControllerServerRegistrar;

#[CoversClass(AddGrpcTimeout::class)]
final class AddGrpcTimeoutTest extends GrpcTestCase
{
    public function testTimeoutHeaderSet(): void
    {
        $server = (new ServerBuilder())
            ->withAddress('0.0.0.0:3000')
            ->registerFromService(new TestControllerServerRegistrar(new class extends TestControllerServer {
                public function addTask(AddTaskRequest $request, Cancellation $cancellation = new NullCancellation()): AddTaskResponse
                {
                    return new AddTaskResponse($request->id * 2);
                }
            }))
            ->build()
        ;

        $server->serve();

        $grpcClient = (new ClientBuilder())
            ->withInterceptor(new AddGrpcTimeout(Timeout::seconds(2)))
            ->withInterceptor($interceptor = new RememberRequestInterceptor())
            ->build(new ClientOptions('http://localhost:3000'))
        ;

        $client = new TestControllerClient($grpcClient);
        $response = $client->addTask(new AddTaskRequest(14));
        self::assertEquals(new AddTaskResponse(28), $response);
        self::assertEquals('2S', $interceptor->request?->getHeader('grpc-timeout'));

        $server->shutdown();
    }
}
