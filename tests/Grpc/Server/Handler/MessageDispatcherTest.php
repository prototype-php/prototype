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

namespace Prototype\Tests\Grpc\Server\Handler;

use PHPUnit\Framework\Attributes\CoversClass;
use Prototype\Byte\Buffer;
use Prototype\Grpc\Internal\Net\Endpoint;
use Prototype\Grpc\Server\Internal\Handler\Message;
use Prototype\Grpc\Server\Internal\Handler\MessageDispatcher;
use Prototype\Grpc\Server\RpcMethod;
use Prototype\Serializer\Serializer;
use Prototype\Tests\Grpc\GrpcTestCase;
use Test\Api\V1\AddTaskRequest;
use Test\Api\V1\AddTaskResponse;

#[CoversClass(MessageDispatcher::class)]
final class MessageDispatcherTest extends GrpcTestCase
{
    public function testMessageDispatch(): void
    {
        $serializer = new Serializer();

        $dispatcher = new MessageDispatcher([
            'test.api.v1.TaskController' => [
                'AddTask' => new RpcMethod(
                    'AddTask',
                    RpcMethod::createHandler(
                        static function (AddTaskRequest $request): AddTaskResponse {
                            return new AddTaskResponse($request->id);
                        },
                        AddTaskRequest::class,
                    ),
                ),
            ],
            $serializer,
        ]);

        $responseMessage = $dispatcher->dispatch(
            Endpoint::parse('/test.api.v1.TaskController/AddTask'),
            new Message($serializer->serialize(new AddTaskRequest(33))->reset()),
        );
        self::assertEquals(new AddTaskResponse(33), $serializer->deserialize(Buffer::fromString($responseMessage->body ?: ''), AddTaskResponse::class));
    }
}
