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

namespace Prototype\Tests\Grpc\Client;

use Amp\Http\Client\DelegateHttpClient;
use Amp\Http\Client\Request;
use Amp\Http\Client\Response;
use Amp\Http\HttpStatus;
use Kafkiansky\Binary\Buffer;
use Kafkiansky\Binary\Endianness;
use PHPUnit\Framework\Attributes\CoversClass;
use Prototype\Grpc\Client\Client;
use Prototype\Grpc\Client\ClientBuilder;
use Prototype\Grpc\Client\ClientOptions;
use Prototype\Grpc\Client\GrpcRequest;
use Prototype\Grpc\Client\GrpcResponse;
use Prototype\Grpc\Client\Internal\Wire\RequestFactory;
use Prototype\Grpc\Client\Internal\Wire\ResponseFactory;
use Prototype\Grpc\Client\RequestException;
use Prototype\Grpc\Compression\GZIPCompressor;
use Prototype\Grpc\StatusCode;
use Prototype\Serializer\Serializer;
use Prototype\Tests\Grpc\GrpcTestCase;
use Test\Api\V1\AddTaskRequest;
use Test\Api\V1\AddTaskResponse;
use Test\Api\V1\AddTaskResponseErrorType;
use Test\Api\V1\TestControllerClient;

#[CoversClass(Client::class)]
#[CoversClass(ClientBuilder::class)]
#[CoversClass(GrpcRequest::class)]
#[CoversClass(GrpcResponse::class)]
#[CoversClass(ClientOptions::class)]
#[CoversClass(RequestFactory::class)]
#[CoversClass(ResponseFactory::class)]
final class ClientTest extends GrpcTestCase
{
    private Serializer $serializer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->serializer = new Serializer();
    }

    public function testGeneratedClient(): void
    {
        $http2Client = $this->createMock(DelegateHttpClient::class);
        $http2Client
            ->expects(self::once())
            ->method('request')
            ->with(self::callback(static function (Request $request): bool {
                self::assertSame(
                    [
                        ['Content-Type', 'application/grpc'],
                        ['User-Agent', 'grpc-php-prototype/dev'],
                        ['TE', 'trailers'],
                        ['grpc-encoding', 'identity'],
                    ],
                    $request->getHeaderPairs(),
                );
                self::assertSame('POST', $request->getMethod());
                self::assertSame('/test.api.v1.TestController/AddTask', $request->getUri()->getPath());

                return true;
            }))
            ->willReturn(
                new Response(
                    '2',
                    HttpStatus::OK,
                    reason: '',
                    headers: [
                        'grpc-status' => (string)StatusCode::OK->value,
                        'grpc-message' => StatusCode::OK->name,
                        'Content-Type' => 'application/grpc',
                        'grpc-encoding' => 'identity',
                    ],
                    body: Buffer::empty(Endianness::network())
                        ->writeInt8(0)
                        ->writeUint32(\strlen($out = $this->serializer->serialize($response = new AddTaskResponse(14, AddTaskResponseErrorType::BAD_REQUEST))->reset()))
                        ->write($out)
                        ->reset(),
                    request: new Request('/test.api.v1.TestController/AddTask'),
                ))
        ;

        $grpcClient = (new ClientBuilder())
            ->withHTTPClient($http2Client)
            ->build(
                new ClientOptions('http://localhost:5000'),
            )
        ;

        $client = new TestControllerClient($grpcClient);
        self::assertEquals($response, $client->addTask(new AddTaskRequest(14,'test', ['recurrent'])));
    }

    public function testGeneratedClientWithCompression(): void
    {
        $compressor = new GZIPCompressor();

        $http2Client = $this->createMock(DelegateHttpClient::class);
        $http2Client
            ->expects(self::once())
            ->method('request')
            ->with(self::callback(static function (Request $request) use ($compressor): bool {
                self::assertSame(
                    [
                        ['Content-Type', 'application/grpc'],
                        ['User-Agent', 'grpc-php-prototype/dev'],
                        ['TE', 'trailers'],
                        ['grpc-encoding', $compressor->name()],
                    ],
                    $request->getHeaderPairs(),
                );
                self::assertSame('POST', $request->getMethod());
                self::assertSame('/test.api.v1.TestController/AddTask', $request->getUri()->getPath());

                return true;
            }))
            ->willReturn(
                new Response(
                    '2',
                    HttpStatus::OK,
                    reason: '',
                    headers: [
                        'grpc-status' => (string)StatusCode::OK->value,
                        'grpc-message' => StatusCode::OK->name,
                        'Content-Type' => 'application/grpc',
                        'grpc-encoding' => $compressor->name(),
                    ],
                    body: Buffer::empty(Endianness::network())
                        ->writeInt8(1)
                        ->writeUint32(\strlen($out = $compressor->compress($this->serializer->serialize($response = new AddTaskResponse(errorType: AddTaskResponseErrorType::BAD_REQUEST))->reset())))
                        ->write($out)
                        ->reset(),
                    request: new Request('/test.api.v1.TestController/AddTask'),
                ))
        ;

        $grpcClient = (new ClientBuilder())
            ->withHTTPClient($http2Client)
            ->withCompressor($compressor)
            ->build(
                new ClientOptions('http://localhost:5000'),
            )
        ;

        $client = new TestControllerClient($grpcClient);
        self::assertEquals($response, $client->addTask(new AddTaskRequest(14, 'test', ['recurrent'])));
    }

    public function testGeneratedClientWithError(): void
    {
        $http2Client = $this->createMock(DelegateHttpClient::class);
        $http2Client
            ->expects(self::once())
            ->method('request')
            ->with(self::callback(static function (Request $request): bool {
                self::assertSame(
                    [
                        ['Content-Type', 'application/grpc'],
                        ['User-Agent', 'grpc-php-prototype/dev'],
                        ['TE', 'trailers'],
                        ['grpc-encoding', 'identity'],
                    ],
                    $request->getHeaderPairs(),
                );
                self::assertSame('POST', $request->getMethod());
                self::assertSame('/test.api.v1.TestController/AddTask', $request->getUri()->getPath());

                return true;
            }))
            ->willReturn(
                new Response(
                    '2',
                    HttpStatus::OK,
                    reason: '',
                    headers: [
                        'grpc-status' => (string)StatusCode::UNIMPLEMENTED->value,
                        'grpc-message' => StatusCode::UNIMPLEMENTED->name,
                        'Content-Type' => 'application/grpc',
                        'grpc-encoding' => 'identity',
                    ],
                    body: '',
                    request: new Request('/test.api.v1.TestController/AddTask'),
                ))
        ;

        $grpcClient = (new ClientBuilder())
            ->withHTTPClient($http2Client)
            ->build(
                new ClientOptions('http://localhost:5000'),
            )
        ;

        $client = new TestControllerClient($grpcClient);
        self::expectException(RequestException::class);
        self::expectExceptionMessage('Request terminated with error: UNIMPLEMENTED (12).');
        $client->addTask(new AddTaskRequest(14, 'test', ['recurrent']));
    }
}
