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

namespace Prototype\Serializer\Internal\Reflection;

use Prototype\Byte;
use Prototype\Serializer\Exception\EnumDoesNotContainVariant;
use Prototype\Serializer\Exception\EnumDoesNotContainZeroVariant;
use Prototype\Serializer\Internal\Label\Labels;
use Prototype\Serializer\Internal\Type\TypeSerializer;
use Prototype\Serializer\Internal\Type\VarintType;
use Prototype\Serializer\Internal\Wire;
use Typhoon\TypedMap\TypedMap;

/**
 * @internal
 * @psalm-internal Prototype\Serializer
 * @template T of \BackedEnum
 * @template-implements PropertyMarshaller<T>
 */
final class EnumPropertyMarshaller implements PropertyMarshaller
{
    /** @var TypeSerializer<int>  */
    private readonly TypeSerializer $type;

    /**
     * @psalm-param enum-string<T> $enumName
     */
    public function __construct(
        private readonly string $enumName,
    ) {
        $this->type = new VarintType();
    }

    /**
     * {@inheritdoc}
     */
    public function deserializeValue(Byte\Reader $reader, Deserializer $deserializer, Wire\Tag $tag): \BackedEnum
    {
        return $this->enumName::tryFrom($variant = $this->type->readFrom($reader)) ?: throw new EnumDoesNotContainVariant(
            $this->enumName,
            $variant,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function serializeValue(Byte\Writer $writer, Serializer $serializer, mixed $value, Wire\Tag $tag): void
    {
        $this->type->writeTo($writer, (int)$value->value);
    }

    /**
     * {@inheritdoc}
     */
    public function matchValue(mixed $value): bool
    {
        return $value instanceof $this->enumName;
    }

    /**
     * {@inheritdoc}
     */
    public function labels(): TypedMap
    {
        return $this->type->labels()
            ->with(Labels::default, $this->enumName::tryFrom(0) ?: throw new EnumDoesNotContainZeroVariant($this->enumName))
            ->with(Labels::isEmpty, static fn (\BackedEnum $enum): bool => 0 === $enum->value)
            ;
    }
}
