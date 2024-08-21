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

namespace Prototype\Grpc\Server;

use Amp\Cancellation;

/**
 * @api
 */
final class RpcMethod
{
    /**
     * @template TReq of object
     * @template TResp of object
     * @param non-empty-string $name
     * @param \Closure(callable(class-string<TReq>): TReq, Cancellation): TResp $handler
     */
    public function __construct(
        public readonly string $name,
        public readonly \Closure $handler,
    ) {}

    /**
     * @template TReq of object
     * @template TResp of object
     * @param \Closure(TReq, Cancellation): TResp $handler
     * @param class-string<TReq> $requestType
     * @psalm-return \Closure(callable(class-string<TReq>): TReq, Cancellation)
     */
    public static function createHandler(\Closure $handler, string $requestType): \Closure
    {
        return static function (callable $deserialize, Cancellation $cancellation) use ($handler, $requestType): object {
            return $handler($deserialize($requestType), $cancellation);
        };
    }
}
