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

use Kafkiansky\Binary;

/**
 * @api
 */
final class Buffer implements
    Reader,
    Writer
{
    private function __construct(
        private readonly Binary\Buffer $buffer,
    ) {}

    /**
     * @throws ByteException
     */
    public static function default(): self
    {
        try {
            return self::fromBinaryBuffer(Binary\Buffer::empty(Binary\Endianness::little()));
        } catch (Binary\BinaryException $e) {
            throw PlatformCannotBeIdentified::fromException($e);
        }
    }

    public static function fromBinaryBuffer(Binary\Buffer $buffer): self
    {
        return new self($buffer);
    }

    /**
     * @throws ByteException
     */
    public static function fromString(string $bytes): self
    {
        try {
            return '' !== $bytes
                ? new self(Binary\Buffer::fromString($bytes, Binary\Endianness::little()))
                : self::default()
                ;
        } catch (Binary\BinaryException $e) {
            throw PlatformCannotBeIdentified::fromException($e);
        }
    }

    /**
     * @param resource $stream
     * @throws ByteException
     */
    public static function fromResource($stream): self
    {
        try {
            return new self(Binary\Buffer::fromResource($stream, Binary\Endianness::little()));
        } catch (Binary\BinaryException $e) {
            throw PlatformCannotBeIdentified::fromException($e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function writeFloat(float $value): static
    {
        $this->buffer->writeFloat($value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function readFloat(): float
    {
        try {
            return $this->buffer->consumeFloat();
        } catch (Binary\BinaryException $e) {
            throw BytesCannotBeRead::fromException($e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function writeDouble(float $value): static
    {
        $this->buffer->writeDouble($value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function readDouble(): float
    {
        try {
            return $this->buffer->consumeDouble();
        } catch (Binary\BinaryException $e) {
            throw BytesCannotBeRead::fromException($e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function writeBool(bool $value): static
    {
        return $this->writeVarint((int)$value);
    }

    /**
     * {@inheritdoc}
     */
    public function readBool(): bool
    {
        return 0 !== $this->readVarint();
    }

    /**
     * {@inheritdoc}
     */
    public function writeVarint(int $value): static
    {
        /** @var numeric-string $value */
        $value  = \sprintf('%u', $value);

        while (bccomp($value, '127') > 0) {
            $byte = bcmod($value, '128');
            $value = bcdiv($value, '128');
            $this->write(\chr((int)$byte + 0x80));
        }

        $this->write(\chr((int)bcmod($value, '128')));

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function readVarint(): int
    {
        try {
            return $this->buffer->consumeVarUint();
        } catch (Binary\BinaryException $e) {
            throw BytesCannotBeRead::fromException($e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function readInt32Varint(): int
    {
        /** @var int<-2147483648, 2147483647> */
        return self::decodeZigZag($this->readVarint());
    }

    /**
     * {@inheritdoc}
     */
    public function readInt64Varint(): int
    {
        /** @var int<min, max> */
        return self::decodeZigZag($this->readVarint());
    }

    /**
     * {@inheritdoc}
     */
    public function writeInt32Varint(int $value): static
    {
        return $this->writeZigZagVarint($value, self::encodeZigZag32(...));
    }

    /**
     * {@inheritdoc}
     */
    public function writeInt64Varint(int $value): static
    {
        return $this->writeZigZagVarint($value, self::encodeZigZag64(...));
    }

    /**
     * {@inheritdoc}
     */
    public function writeFixed32(int $value): static
    {
        try {
            $this->buffer->writeUint32($value);

            return $this;
        } catch (Binary\BinaryException $e) {
            throw BytesCannotBeWritten::fromException($e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function readFixed32(): int
    {
        try {
            /** @var int<0, 4294967295> */
            return $this->buffer->consumeUint32();
        } catch (Binary\BinaryException $e) {
            throw BytesCannotBeRead::fromException($e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function writeFixed64(int $value): static
    {
        try {
            $this->buffer->writeUint64($value);

            return $this;
        } catch (Binary\BinaryException $e) {
            throw BytesCannotBeWritten::fromException($e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function readFixed64(): int
    {
        try {
            /** @var int<0, max> */
            return $this->buffer->consumeUint64();
        } catch (Binary\BinaryException $e) {
            throw BytesCannotBeRead::fromException($e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function writeSFixed32(int $value): static
    {
        try {
            $this->buffer->writeInt32($value);

            return $this;
        } catch (Binary\BinaryException $e) {
            throw BytesCannotBeWritten::fromException($e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function readSFixed32(): int
    {
        try {
            /** @var int<-2147483648, 2147483647> */
            return $this->buffer->consumeInt32();
        } catch (Binary\BinaryException $e) {
            throw BytesCannotBeRead::fromException($e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function writeSFixed64(int $value): static
    {
        try {
            $this->buffer->writeInt64($value);

            return $this;
        } catch (Binary\BinaryException $e) {
            throw BytesCannotBeWritten::fromException($e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function readSFixed64(): int
    {
        try {
            /** @var int<min, max> */
            return $this->buffer->consumeInt64();
        } catch (Binary\BinaryException $e) {
            throw BytesCannotBeRead::fromException($e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function writeString(string $value): static
    {
        return $this->writeVarint(\strlen($value))->write($value);
    }

    /**
     * {@inheritdoc}
     */
    public function readString(): string
    {
        return $this->read($this->readVarint());
    }

    /**
     * {@inheritdoc}
     */
    public function write(string $bytes): static
    {
        try {
            $this->buffer->write($bytes);

            return $this;
        } catch (Binary\BinaryException $e) {
            throw BytesCannotBeWritten::fromException($e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function read(int $n): string
    {
        try {
            return $this->buffer->consume($n);
        } catch (Binary\BinaryException $e) {
            throw BytesCannotBeRead::fromException($e);
        }
    }

    public function isNotEmpty(): bool
    {
        return !$this->buffer->isEmpty();
    }

    /**
     * {@inheritdoc}
     */
    public function slice(): static
    {
        try {
            return self::fromBinaryBuffer($this->buffer->split($this->readVarint()));
        } catch (Binary\BinaryException $e) {
            throw BytesCannotBeRead::fromException($e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function clone(): static
    {
        try {
            return self::fromBinaryBuffer($this->buffer->clone());
        } catch (Binary\BinaryException $e) {
            throw BytesCannotBeRead::fromException($e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function copyFrom(Writer $writer): static
    {
        return $this
            ->writeVarint($writer->size())
            ->write($writer->reset())
            ;
    }

    public function size(): int
    {
        return $this->buffer->count();
    }

    /**
     * {@inheritdoc}
     */
    public function reset(): string
    {
        try {
            return $this->buffer->reset();
        } catch (Binary\BinaryException $e) {
            throw BytesCannotBeRead::fromException($e);
        }
    }

    /**
     * @param callable(int): int $encodeZigZag
     * @throws ByteException
     */
    private function writeZigZagVarint(int $value, callable $encodeZigZag): self
    {
        $this->writeVarint($encodeZigZag($value));

        return $this;
    }

    private static function encodeZigZag32(int $value): int
    {
        return ($value << 1) ^ ($value >> 32 - 1);
    }

    private static function encodeZigZag64(int $value): int
    {
        return ($value << 1) ^ ($value >> 64 - 1);
    }

    private static function decodeZigZag(int $value): int
    {
        return ($value >> 1) ^ -($value & 1);
    }
}
