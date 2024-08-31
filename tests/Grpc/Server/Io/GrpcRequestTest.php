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

namespace Prototype\Tests\Grpc\Server\Io;

use Amp\Http\Server\Driver\Client;
use Amp\Http\Server\Request;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Prototype\Grpc\Server\Internal\Exception\ServerException;
use Prototype\Grpc\Server\Internal\Io\GrpcRequest;
use Psr\Http\Message\UriInterface;

#[CoversClass(GrpcRequest::class)]
final class GrpcRequestTest extends TestCase
{
    public function testBadContentType(): void
    {
        self::expectException(ServerException::class);
        self::expectExceptionMessage('Invalid gRPC request content-type "application/json".');

        GrpcRequest::fromServerRequest(new Request(
            $this->createStub(Client::class),
            'POST',
            $this->createStub(UriInterface::class),
            [
                'content-type' => 'application/json',
            ],
        ));
    }
}
