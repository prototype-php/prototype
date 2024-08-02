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

namespace Prototype\Byte;

/**
 * @api
 */
interface Reader extends
    Sizeable,
    Resettable,
    Cloneable
{
    /**
     * @throws ByteException
     */
    public function readFloat(): float;

    /**
     * @return double
     * @throws ByteException
     */
    public function readDouble(): float;

    /**
     * @throws ByteException
     */
    public function readBool(): bool;

    /**
     * @throws ByteException
     */
    public function readVarint(): int;

    /**
     * @return int<-2147483648, 2147483647>
     * @throws ByteException
     */
    public function readInt32Varint(): int;

    /**
     * @return int<min, max>
     * @throws ByteException
     */
    public function readInt64Varint(): int;

    /**
     * @return int<0, 4294967295>
     * @throws ByteException
     */
    public function readFixed32(): int;

    /**
     * @return int<0, max>
     * @throws ByteException
     */
    public function readFixed64(): int;

    /**
     * @return int<-2147483648, 2147483647>
     * @throws ByteException
     */
    public function readSFixed32(): int;

    /**
     * @return int<min, max>
     * @throws ByteException
     */
    public function readSFixed64(): int;

    /**
     * @throws ByteException
     */
    public function readString(): string;

    /**
     * @throws ByteException
     * @psalm-return ($n is positive-int ? non-empty-string : string)
     */
    public function read(int $n): string;

    /**
     * @throws ByteException
     */
    public function slice(): static;
}
