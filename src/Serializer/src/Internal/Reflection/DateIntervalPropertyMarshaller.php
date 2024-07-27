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

namespace Kafkiansky\Prototype\Internal\Reflection;

use Kafkiansky\Binary;
use Kafkiansky\Prototype\Exception\PropertyValueIsInvalid;
use Kafkiansky\Prototype\Internal\Label\Labels;
use Kafkiansky\Prototype\Internal\Type\DurationType;
use Kafkiansky\Prototype\Internal\Wire;
use Typhoon\TypedMap\TypedMap;

/**
 * @internal
 * @psalm-internal Kafkiansky\Prototype
 * @template-implements PropertyMarshaller<\DateInterval>
 */
final class DateIntervalPropertyMarshaller implements PropertyMarshaller
{
    /**
     * {@inheritdoc}
     */
    public function deserializeValue(Binary\Buffer $buffer, Deserializer $deserializer, Wire\Tag $tag): \DateInterval
    {
        $duration = $deserializer->deserialize(DurationType::class, $buffer->split($buffer->consumeVarUint()));

        try {
            return new \DateInterval(\sprintf('PT%dS', $duration->seconds + $duration->nanos / 1e9));
        } catch (\Throwable $e) {
            throw new PropertyValueIsInvalid(\DateInterval::class, $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function serializeValue(Binary\Buffer $buffer, Serializer $serializer, mixed $value, Wire\Tag $tag): void
    {
        /** @psalm-suppress ArgumentTypeCoercion */
        $serializer->serialize(
            new DurationType(
                $value->days * 24 * 60 * 60 +
                $value->h * 60 * 60 +
                $value->i * 60 +
                $value->s,
                (int) ($value->f * 1_000_000_000),
            ),
            $objectBuffer = $buffer->clone(),
        );

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
        return $value instanceof \DateInterval;
    }

    /**
     * {@inheritdoc}
     */
    public function labels(): TypedMap
    {
        return Labels::new(Wire\Type::BYTES);
    }
}
