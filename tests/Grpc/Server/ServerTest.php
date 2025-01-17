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

namespace Prototype\Tests\Grpc\Server;

use Amp\Cancellation;
use Amp\NullCancellation;
use PHPUnit\Framework\Attributes\CoversClass;
use Prototype\Grpc\Client\ClientBuilder;
use Prototype\Grpc\Client\ClientOptions;
use Prototype\Grpc\Client\RequestException;
use Prototype\Grpc\Compression\GZIPCompressor;
use Prototype\Grpc\Interceptor\AddGrpcTimeout;
use Prototype\Grpc\Server\Internal\Adapter\ServerRequestHandler;
use Prototype\Grpc\Server\Internal\Cancellation\CancellationFactory;
use Prototype\Grpc\Server\Internal\Handler\InterceptedGrpcRequestHandler;
use Prototype\Grpc\Server\Internal\Io\GrpcRequest;
use Prototype\Grpc\Server\Internal\Transport\OnlyHttp2DriverFactory;
use Prototype\Grpc\Server\RpcMethod;
use Prototype\Grpc\Server\Server;
use Prototype\Grpc\Server\ServerBuilder;
use Prototype\Grpc\Server\ServiceDescriptor;
use Prototype\Grpc\Timeout;
use Prototype\Tests\Grpc\GrpcTestCase;
use Test\Api\V1\AddTaskRequest;
use Test\Api\V1\AddTaskResponse;
use Test\Api\V1\AddTaskResponseErrorType;
use Test\Api\V1\TestControllerClient;
use Test\Api\V1\TestControllerServer;
use Test\Api\V1\TestControllerServerRegistrar;
use function Amp\delay;

#[CoversClass(Server::class)]
#[CoversClass(ServerBuilder::class)]
#[CoversClass(OnlyHttp2DriverFactory::class)]
#[CoversClass(InterceptedGrpcRequestHandler::class)]
#[CoversClass(ServerRequestHandler::class)]
#[CoversClass(CancellationFactory::class)]
#[CoversClass(RpcMethod::class)]
#[CoversClass(ServiceDescriptor::class)]
#[CoversClass(GrpcRequest::class)]
final class ServerTest extends GrpcTestCase
{
    public function testGeneratedServer(): void
    {
        /** @var object[] $requests */
        $requests = [];

        $server = (new ServerBuilder())
            ->withAddress('0.0.0.0:3000')
            ->registerFromService(new TestControllerServerRegistrar(new class($requests) extends TestControllerServer {
                public function __construct(
                    private array &$requests,
                ) {}

                public function addTask(AddTaskRequest $request, Cancellation $cancellation = new NullCancellation()): AddTaskResponse
                {
                    $this->requests[] = $request;

                    return new AddTaskResponse($request->id * 2, AddTaskResponseErrorType::UNSPECIFIED);
                }
            }))
            ->build()
        ;

        $server->serve();

        $grpcClient = (new ClientBuilder())
            ->build(new ClientOptions('http://localhost:3000'))
        ;

        $client = new TestControllerClient($grpcClient);
        $response = $client->addTask($request = new AddTaskRequest(14, 'test', ['recurrent']));
        self::assertCount(1, $requests);
        self::assertEquals([$request], $requests);
        self::assertEquals(new AddTaskResponse(28), $response);

        $server->shutdown();
    }

    public function testGeneratedServerWithCompression(): void
    {
        $compressor = new GZIPCompressor();

        /** @var object[] $requests */
        $requests = [];

        $server = (new ServerBuilder())
            ->withAddress('0.0.0.0:3000')
            ->withCompressor($compressor)
            ->registerFromService(new TestControllerServerRegistrar(new class($requests) extends TestControllerServer {
                public function __construct(
                    private array &$requests,
                ) {}

                public function addTask(AddTaskRequest $request, Cancellation $cancellation = new NullCancellation()): AddTaskResponse
                {
                    $this->requests[] = $request;

                    return new AddTaskResponse(errorType: AddTaskResponseErrorType::BAD_REQUEST);
                }
            }))
            ->build()
        ;

        $server->serve();

        $grpcClient = (new ClientBuilder())
            ->withCompressor($compressor)
            ->build(new ClientOptions('http://localhost:3000'))
        ;

        $client = new TestControllerClient($grpcClient);
        $response = $client->addTask($request = new AddTaskRequest(14, 'test', ['recurrent']));
        self::assertCount(1, $requests);
        self::assertEquals([$request], $requests);
        self::assertEquals(new AddTaskResponse(errorType: AddTaskResponseErrorType::BAD_REQUEST), $response);

        $server->shutdown();
    }

    public function testGeneratedServerNotImplemented(): void
    {
        $server = (new ServerBuilder())
            ->withAddress('0.0.0.0:3000')
            ->registerFromService(new TestControllerServerRegistrar(new class extends TestControllerServer {}))
            ->build()
        ;

        $server->serve();

        $grpcClient = (new ClientBuilder())
            ->build(new ClientOptions('http://localhost:3000'))
        ;

        $client = new TestControllerClient($grpcClient);

        try {
            $client->addTask(new AddTaskRequest(14, 'test', ['recurrent']));
            self::assertTrue(false);
        } catch (\Throwable $e) {
            self::assertInstanceOf(RequestException::class, $e);
            self::assertSame('Request terminated with error: Rpc "/test.api.v1.TestController/AddTask" is not implemented yet. (12).', $e->getMessage());
        }

        $server->shutdown();
    }

    public function testClientTimeout(): void
    {
        $server = (new ServerBuilder())
            ->withAddress('0.0.0.0:3000')
            ->withRequestTimeout(Timeout::seconds(1))
            ->registerFromService(new TestControllerServerRegistrar(new class extends TestControllerServer {
                public function addTask(AddTaskRequest $request, Cancellation $cancellation = new NullCancellation(),): AddTaskResponse
                {
                    delay(0.500, cancellation: $cancellation);

                    return new AddTaskResponse();
                }
            }))
            ->build()
        ;

        $server->serve();

        $grpcClient = (new ClientBuilder())
            ->withInterceptor(new AddGrpcTimeout(Timeout::milliseconds(100)))
            ->build(new ClientOptions('http://localhost:3000'))
        ;

        $client = new TestControllerClient($grpcClient);

        try {
            $client->addTask(new AddTaskRequest(14, 'test', ['recurrent']));
            self::assertTrue(false);
        } catch (\Throwable $e) {
            self::assertInstanceOf(RequestException::class, $e);
            self::assertSame('Request terminated with error: DEADLINE_EXCEEDED (4).', $e->getMessage());
        }

        $server->shutdown();
    }

    public function testServerTimeout(): void
    {
        $server = (new ServerBuilder())
            ->withAddress('0.0.0.0:3000')
            ->withRequestTimeout(Timeout::milliseconds(100))
            ->registerFromService(new TestControllerServerRegistrar(new class extends TestControllerServer {
                public function addTask(AddTaskRequest $request, Cancellation $cancellation = new NullCancellation(),): AddTaskResponse
                {
                    delay(0.500, cancellation: $cancellation);

                    return new AddTaskResponse();
                }
            }))
            ->build()
        ;

        $server->serve();

        $grpcClient = (new ClientBuilder())
            ->build(new ClientOptions('http://localhost:3000'))
        ;

        $client = new TestControllerClient($grpcClient);

        try {
            $client->addTask(new AddTaskRequest(14, 'test', ['recurrent']));
            self::assertTrue(false);
        } catch (\Throwable $e) {
            self::assertInstanceOf(RequestException::class, $e);
            self::assertSame('Request terminated with error: DEADLINE_EXCEEDED (4).', $e->getMessage());
        }

        $server->shutdown();
    }
}
