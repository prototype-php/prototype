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

namespace Prototype\Grpc\Server\Internal\Io;

use Amp\Http\Server\Request;
use Amp\Http\Server\RequestBody;
use Prototype\Grpc\Internal\Net\Endpoint;

/**
 * @internal
 * @psalm-internal Prototype\Grpc
 */
final class GrpcRequest
{
    public function __construct(
        public readonly Endpoint $endpoint,
        public readonly RequestBody $body,
        public readonly ?string $encoding = null,
    ) {}

    public static function fromServerRequest(Request $request): self
    {
        return new self(
            Endpoint::parse($request->getUri()->getPath()),
            $request->getBody(),
            $request->getHeader('grpc-encoding'),
        );
    }
}
