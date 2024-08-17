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

namespace Prototype\Grpc\Internal\Wire;

use Amp\Http\Client\BufferedContent;
use Amp\Http\Client\Request;
use Kafkiansky\Binary\Buffer;
use Kafkiansky\Binary\Endianness;
use Prototype\Grpc\Compression\Compressor;
use Prototype\Serializer\Serializer;

/**
 * @internal
 * @psalm-internal Prototype\Grpc
 */
final class RequestFactory
{
    private readonly Buffer $buffer;

    public function __construct(
        private readonly Serializer $serializer,
        private readonly Compressor $compressor,
    ) {
        $this->buffer = Buffer::empty(Endianness::network());
    }

    /**
     * @throws \Kafkiansky\Binary\BinaryException
     * @throws \Prototype\Byte\ByteException
     * @throws \Prototype\GRPC\Compression\CompressionException
     * @throws \Prototype\Serializer\PrototypeException
     * @throws \ReflectionException
     */
    public function newRequest(object $message, string $uri): Request
    {
        $messageBuffer = $this->serializer->serialize($message);

        $bytes = $compressed = $messageBuffer->reset();

        if ('' !== $bytes) {
            $compressed = $this->compressor->compress($bytes);
        }

        $requestBuffer = $this->buffer
            ->clone()
            ->writeInt8((int)($bytes !== $compressed))
            ->writeUint32(\strlen($compressed))
            ->write($compressed)
        ;

        $request = new Request($uri, 'POST');
        $request->setProtocolVersions(['2']);
        $request->setHeaders([
            'Content-Type' => 'application/grpc',
            'TE' => 'trailers',
            'grpc-encoding' => $this->compressor->name(),
        ]);
        $request->setBody(BufferedContent::fromString($requestBuffer->reset()));

        return $request;
    }
}
