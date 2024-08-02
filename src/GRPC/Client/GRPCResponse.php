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

namespace Prototype\GRPC\Client;

use Prototype\GRPC\StatusCode;

/**
 * @api
 * @template T of object
 */
final class GRPCResponse
{
    /**
     * @param T $message
     * @param array<non-empty-string, list<string>> $headers
     */
    private function __construct(
        public readonly StatusCode $statusCode,
        public readonly mixed $message = null,
        public readonly array $headers = [],
        public readonly ?string $grpcMessage = null,
    ) {}

    /**
     * @template E of object
     * @param E $message
     * @param array<non-empty-string, list<string>> $headers
     * @return self<E>
     */
    public static function ok(
        StatusCode $statusCode,
        object $message,
        array $headers,
    ): self {
        return new self(
            $statusCode,
            $message,
            $headers,
        );
    }

    /**
     * @param array<non-empty-string, list<string>> $headers
     */
    public static function error(
        StatusCode $statusCode,
        array $headers,
        ?string $grpcMessage = null,
    ): self {
        return new self(
            $statusCode,
            headers: $headers,
            grpcMessage: $grpcMessage,
        );
    }
}
