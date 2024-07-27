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

namespace Prototype\Serializer\Internal\Wire;

/**
 * @internal
 * @psalm-internal Prototype\Serializer
 * @psalm-type ValueType = scalar|object|array<array-key, mixed>|null
 */
final class ValueContext
{
    /** @var ValueType  */
    private mixed $value = null;

    /**
     * @param ValueType|\Traversable $value
     */
    public function setValue(mixed $value): void
    {
        if ($value instanceof \Traversable) {
            $value = iterator_to_array($value);
        }

        $this->value = match (true) {
            \is_array($value) => array_merge(!\is_array($this->value) ? [] : $this->value, $value),
            default => $value,
        };
    }

    /**
     * @return ValueType
     */
    public function getValue(): mixed
    {
        return $this->value;
    }
}
