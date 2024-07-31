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
use Prototype\Serializer\Internal\Wire;
use Typhoon\TypedMap\TypedMap;

/**
 * @internal
 * @psalm-internal Prototype\Serializer
 * @template T
 * @template-implements PropertyMarshaller<iterable<T>>
 */
final class ArrayPropertyMarshaller implements PropertyMarshaller
{
    /**
     * @param PropertyMarshaller<T> $marshaller
     */
    public function __construct(
        private readonly PropertyMarshaller $marshaller,
    ) {}

    /**
     * {@inheritdoc}
     */
    public function deserializeValue(Binary\Buffer $buffer, Deserializer $deserializer, Wire\Tag $tag): iterable
    {
        if ($this->marshaller->labels()[Labels::packed]) {
            $buffer = $buffer->split($buffer->consumeVarUint());

            while (!$buffer->isEmpty()) {
                yield $this->marshaller->deserializeValue($buffer, $deserializer, $tag);
            }

            return;
        }

        yield $this->marshaller->deserializeValue($buffer, $deserializer, $tag);
    }

    /**
     * {@inheritdoc}
     */
    public function serializeValue(Binary\Buffer $buffer, Serializer $serializer, mixed $value, Wire\Tag $tag): void
    {
        $arrayBuffer = $buffer->clone();

        $isPacked = $this->marshaller->labels()[Labels::packed];
        $serializeTag = $this->marshaller->labels()[Labels::serializeTag];

        foreach ($value as $item) {
            $this->marshaller->serializeValue($valueBuffer = $arrayBuffer->clone(), $serializer, $item, $tag);

            if (!$valueBuffer->isEmpty()) {
                if (!$isPacked && $serializeTag) {
                    $tag->encode($arrayBuffer);
                }

                $arrayBuffer->write($valueBuffer->reset());
            }
        }

        if (!$arrayBuffer->isEmpty()) {
            if ($isPacked) {
                $tag->encode($buffer);
                $buffer
                    ->writeVarUint($arrayBuffer->count())
                ;
            }

            $buffer->write($arrayBuffer->reset());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function matchValue(mixed $value): bool
    {
        return \is_array($value) && array_is_list($value);
    }

    /**
     * {@inheritdoc}
     */
    public function labels(): TypedMap
    {
        $labels = $this->marshaller->labels();

        if ($labels[Labels::packed]) {
            $labels = $labels->with(Labels::wireType, Wire\Type::BYTES);
        }

        return $labels
            ->with(Labels::default, [])
            ->with(Labels::isEmpty, static fn (array $values): bool => [] === $values)
            ->with(Labels::serializeTag, false)
            ;
    }
}
