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

namespace Kafkiansky\Prototype\Internal\Wire;

use Kafkiansky\Binary\BinaryException;
use Kafkiansky\Binary\Buffer;
use Kafkiansky\Prototype\Exception\TypeIsUnknown;

/**
 * @internal
 * @psalm-internal Kafkiansky\Prototype
 */
final class Tag
{
    /**
     * @param positive-int $num
     */
    public function __construct(
        public readonly int $num,
        public readonly Type $type,
    ) {}

    /**
     * @throws BinaryException
     * @throws TypeIsUnknown   when protobuf type is not recognized
     */
    public static function decode(Buffer $buffer): self
    {
        $tag = $buffer->consumeVarUint();

        /** @psalm-var positive-int $num */
        $num = $tag >> 3;
        $type = $tag & 7;

        return new self(
            $num,
            Type::tryFrom($type) ?: throw new TypeIsUnknown($type),
        );
    }

    public function encode(Buffer $buffer): void
    {
        $buffer->writeVarUint($this->num << 3 | $this->type->value);
    }
}
