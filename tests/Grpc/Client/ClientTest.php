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
use Prototype\Grpc\Client\RequestException;
use Prototype\Grpc\Compression\GZIPCompressor;
use Prototype\Grpc\StatusCode;
use Prototype\Serializer\Serializer;
use Prototype\Tests\Grpc\GrpcTestCase;

#[CoversClass(Client::class)]
final class ClientTest extends GrpcTestCase
{
    private Serializer $serializer;

    /** @var non-empty-string */
    private static string $proto = <<<'PROTO'
syntax = "proto3";

package test.api.v1;

message AddTaskRequest {
    string name = 1;
    repeated string tags = 2;
}

message AddTaskResponse {
    enum ErrorType {
        UNSPECIFIED = 0;
        BAD_REQUEST = 1;
        INTERNAL = 2;
    }

    ErrorType error_type = 1;
}

service TestController {
    rpc AddTask(AddTaskRequest) returns (AddTaskResponse) {}
}
PROTO;


    protected function setUp(): void
    {
        parent::setUp();

        $this->serializer = new Serializer();
    }

    public function testGeneratedClient(): void
    {
        self::compile(self::$proto);

        self::requireAll([
            'Test/Api/V1/TestControllerClient.php',
            'Test/Api/V1/AddTaskRequest.php',
            'Test/Api/V1/AddTaskResponse.php',
            'Test/Api/V1/AddTaskResponseErrorType.php',
        ]);

        $http2Client = $this->createMock(DelegateHttpClient::class);
        $http2Client
            ->expects(self::once())
            ->method('request')
            ->with(self::callback(static function (Request $request): bool {
                self::assertSame(
                    [
                        ['Content-Type', 'application/grpc'],
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
                        ->writeUint32(\strlen($out = $this->serializer->serialize($response = new \Test\Api\V1\AddTaskResponse(\Test\Api\V1\AddTaskResponseErrorType::BAD_REQUEST))->reset()))
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

        $client = new \Test\Api\V1\TestControllerClient($grpcClient);
        self::assertEquals($response, $client->addTask(new \Test\Api\V1\AddTaskRequest('test', ['recurrent'])));
    }

    public function testGeneratedClientWithCompression(): void
    {
        self::compile(self::$proto);

        self::requireAll([
            'Test/Api/V1/TestControllerClient.php',
            'Test/Api/V1/AddTaskRequest.php',
            'Test/Api/V1/AddTaskResponse.php',
            'Test/Api/V1/AddTaskResponseErrorType.php',
        ]);

        $compressor = new GZIPCompressor();

        $http2Client = $this->createMock(DelegateHttpClient::class);
        $http2Client
            ->expects(self::once())
            ->method('request')
            ->with(self::callback(static function (Request $request) use ($compressor): bool {
                self::assertSame(
                    [
                        ['Content-Type', 'application/grpc'],
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
                        ->writeUint32(\strlen($out = $compressor->compress($this->serializer->serialize($response = new \Test\Api\V1\AddTaskResponse(\Test\Api\V1\AddTaskResponseErrorType::BAD_REQUEST))->reset())))
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

        $client = new \Test\Api\V1\TestControllerClient($grpcClient);
        self::assertEquals($response, $client->addTask(new \Test\Api\V1\AddTaskRequest('test', ['recurrent'])));
    }

    public function testGeneratedClientWithError(): void
    {
        self::compile(self::$proto);

        self::requireAll([
            'Test/Api/V1/TestControllerClient.php',
            'Test/Api/V1/AddTaskRequest.php',
            'Test/Api/V1/AddTaskResponse.php',
            'Test/Api/V1/AddTaskResponseErrorType.php',
        ]);

        $http2Client = $this->createMock(DelegateHttpClient::class);
        $http2Client
            ->expects(self::once())
            ->method('request')
            ->with(self::callback(static function (Request $request): bool {
                self::assertSame(
                    [
                        ['Content-Type', 'application/grpc'],
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

        $client = new \Test\Api\V1\TestControllerClient($grpcClient);
        self::expectException(RequestException::class);
        self::expectExceptionMessage('Request terminated with error: UNIMPLEMENTED (12).');
        $client->addTask(new \Test\Api\V1\AddTaskRequest('test', ['recurrent']));
    }
}
