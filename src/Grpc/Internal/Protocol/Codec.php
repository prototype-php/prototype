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

namespace Prototype\Grpc\Internal\Protocol;

use Kafkiansky\Binary\Buffer;
use Kafkiansky\Binary\Endianness;

/**
 * @internal
 * @psalm-internal Prototype\Grpc
 */
final class Codec
{
    private readonly Buffer $buffer;

    /**
     * @throws \Kafkiansky\Binary\BinaryException
     */
    public function __construct()
    {
        $this->buffer = Buffer::empty(Endianness::network());
    }

    /**
     * @throws \Kafkiansky\Binary\BinaryException
     */
    public function extend(string $bytes): self
    {
        $this->buffer->write($bytes);

        return $this;
    }

    /**
     * @throws \Kafkiansky\Binary\BinaryException
     */
    public function readFrame(): Frame
    {
        $compressed = 1 === $this->buffer->consumeInt8();

        return new Frame(
            $this->buffer->consume($this->buffer->consumeUint32()),
            $compressed,
        );
    }

    /**
     * @throws \Kafkiansky\Binary\BinaryException
     */
    public function writeFrame(Frame $frame): self
    {
        $this
            ->buffer
            ->writeInt8((int)$frame->compressed)
            ->writeUint32(\strlen($frame->payload))
            ->write($frame->payload)
        ;

        return $this;
    }

    /**
     * @throws \Kafkiansky\Binary\BinaryException
     */
    public function buffer(): string
    {
        return $this->buffer->reset();
    }
}
