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
interface Writer extends
    Sizeable,
    Resettable,
    Cloneable
{
    /**
     * @throws ByteException
     */
    public function writeFloat(float $value): static;

    /**
     * @param double $value
     * @throws ByteException
     */
    public function writeDouble(float $value): static;

    /**
     * @throws ByteException
     */
    public function writeBool(bool $value): static;

    /**
     * @throws ByteException
     */
    public function writeVarint(int $value): static;

    /**
     * @param int<-2147483648, 2147483647> $value
     * @throws ByteException
     */
    public function writeInt32Varint(int $value): static;

    /**
     * @param int<min, max> $value
     * @throws ByteException
     */
    public function writeInt64Varint(int $value): static;

    /**
     * @param int<0, 4294967295> $value
     * @throws ByteException
     */
    public function writeFixed32(int $value): static;

    /**
     * @param int<0, max> $value
     * @throws ByteException
     */
    public function writeFixed64(int $value): static;

    /**
     * @param int<-2147483648, 2147483647> $value
     * @throws ByteException
     */
    public function writeSFixed32(int $value): static;

    /**
     * @param int<min, max> $value
     * @throws ByteException
     */
    public function writeSFixed64(int $value): static;

    /**
     * @throws ByteException
     */
    public function writeString(string $value): static;

    /**
     * @throws ByteException
     */
    public function write(string $bytes): static;

    /**
     * @throws ByteException
     */
    public function copyFrom(self $writer): static;
}
