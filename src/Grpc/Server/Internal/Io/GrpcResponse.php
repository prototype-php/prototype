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

use Amp\ByteStream\ReadableStream;
use Amp\Future;
use Amp\Http\HttpStatus;
use Amp\Http\Server\Response;
use Amp\Http\Server\Trailers;
use Prototype\Grpc\StatusCode;

/**
 * @internal
 * @psalm-internal Prototype\Grpc
 */
final class GrpcResponse
{
    /** @var array<non-empty-string, non-empty-string> */
    private static array $headersMap = [
        'Content-Type' => 'application/grpc',
        'TE' => 'trailers',
    ];

    /**
     * @param array<non-empty-string, non-empty-string> $headers
     */
    private function __construct(
        private readonly array $headers,
        private readonly StatusCode $code,
        private readonly ReadableStream|string $body = '',
        private readonly ?Trailers $trailers = null,
        private readonly ?string $message = null,
    ) {}

    public static function error(StatusCode $status = StatusCode::INTERNAL, ?string $message = null): self
    {
        return new self(
            self::$headersMap,
            $status,
            message: $message,
        );
    }

    /**
     * @param array<non-empty-string, non-empty-string> $headers
     * @param ?non-empty-string $encoding
     */
    public static function ok(ReadableStream|string $body = '', array $headers = [], ?string $encoding = null): self
    {
        if (null !== $encoding) {
            $headers['grpc-encoding'] = $encoding;
        }

        return new self(
            array_merge(self::$headersMap, $headers),
            StatusCode::OK,
            $body,
        );
    }

    /**
     * @param array<non-empty-string, non-empty-string> $headers
     */
    public function withHeaders(array $headers): self
    {
        return new self(
            array_merge($this->headers, $headers),
            $this->code,
            $this->body,
            $this->trailers,
            $this->message,
        );
    }

    public function toServerResponse(): Response
    {
        return new Response(
            HttpStatus::OK,
            $this->headers,
            $this->body,
            $this->trailers ?: new Trailers(
                Future::complete([
                    'grpc-status' => (string)$this->code->value,
                    'grpc-message' => $this->message ?: $this->code->name,
                ]),
            ),
        );
    }
}
