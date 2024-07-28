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

use Kafkiansky\Binary;
use Prototype\Serializer\Internal\Label\Labels;
use Prototype\Serializer\Internal\Type\TimestampType;
use Prototype\Serializer\Internal\Wire;
use Typhoon\TypedMap\TypedMap;

/**
 * @internal
 * @psalm-internal Prototype\Serializer
 * @template-implements PropertyMarshaller<\DateTimeInterface>
 */
final class DateTimePropertyMarshaller implements PropertyMarshaller
{
    /**
     * @psalm-param class-string<\DateTimeInterface>|interface-string<\DateTimeInterface> $dateTimeClass
     */
    public function __construct(
        private readonly string $dateTimeClass,
    ) {}

    /**
     * {@inheritdoc}
     */
    public function deserializeValue(Binary\Buffer $buffer, Deserializer $deserializer, Wire\Tag $tag): \DateTimeInterface
    {
        $timestamp = $deserializer->deserialize(TimestampType::class, $buffer->split($buffer->consumeVarUint()));

        return $timestamp->toDateTime($this->dateTimeClass);
    }

    /**
     * {@inheritdoc}
     */
    public function serializeValue(Binary\Buffer $buffer, Serializer $serializer, mixed $value, Wire\Tag $tag): void
    {
        $serializer->serialize(TimestampType::fromDateTime($value), $objectBuffer = $buffer->clone());

        if (!$objectBuffer->isEmpty()) {
            $buffer
                ->writeVarUint($objectBuffer->count())
                ->write($objectBuffer->reset())
            ;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function matchValue(mixed $value): bool
    {
        return $value instanceof \DateTimeInterface;
    }

    /**
     * {@inheritdoc}
     */
    public function labels(): TypedMap
    {
        return Labels::new(Wire\Type::BYTES);
    }
}
