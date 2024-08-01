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

use Prototype\Serializer\Internal\Label\Labels;
use Prototype\Serializer\Internal\Wire;
use Typhoon\TypedMap\TypedMap;
use Prototype\Serializer\Byte;

/**
 * @internal
 * @psalm-internal Prototype\Serializer
 * @template TKey of array-key
 * @template TValue
 * @template-implements PropertyMarshaller<iterable<TKey, TValue>>
 */
final class HashTablePropertyMarshaller implements PropertyMarshaller
{
    /**
     * @param PropertyMarshaller<TKey>   $keyMarshaller
     * @param PropertyMarshaller<TValue> $valueMarshaller
     */
    public function __construct(
        private readonly PropertyMarshaller $keyMarshaller,
        private readonly PropertyMarshaller $valueMarshaller,
    ) {}

    /**
     * {@inheritdoc}
     */
    public function deserializeValue(Byte\Reader $reader, Deserializer $deserializer, Wire\Tag $tag): iterable
    {
        $reader = $reader->slice();

        [$key, $value] = [null, null];

        while ($reader->isNotEmpty()) {
            $tag = Wire\Tag::decode($reader);

            if ($tag->num === 1) {
                $key = $this->keyMarshaller->deserializeValue($reader, $deserializer, $tag);
            } elseif ($tag->num === 2) {
                $value = $this->valueMarshaller->deserializeValue($reader, $deserializer, $tag);
            }
        }

        if (null !== $key) {
            yield $key => $value; // @phpstan-ignore-line
        }
    }

    /**
     * {@inheritdoc}
     */
    public function serializeValue(Byte\Writer $writer, Serializer $serializer, mixed $value, Wire\Tag $tag): void
    {
        foreach ($value as $key => $val) {
            $mapKeyValueBuffer = $writer->clone();

            $keyTag = new Wire\Tag(1, $this->keyMarshaller->labels()[Labels::wireType]);
            $keyTag->encode($mapKeyValueBuffer);

            $this->keyMarshaller->serializeValue(
                $mapKeyValueBuffer,
                $serializer,
                $key,
                $keyTag,
            );

            $valueTag = new Wire\Tag(2, $this->valueMarshaller->labels()[Labels::wireType]);

            $this->valueMarshaller->serializeValue(
                $valueBuffer = $mapKeyValueBuffer->clone(),
                $serializer,
                $val,
                $valueTag,
            );

            if ($valueBuffer->isNotEmpty()) {
                $valueTag->encode($mapKeyValueBuffer);
                $mapKeyValueBuffer->write($valueBuffer->reset());
            }

            $tag->encode($writer);
            $writer->copyFrom($mapKeyValueBuffer);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function matchValue(mixed $value): bool
    {
        return \is_array($value);
    }

    /**
     * {@inheritdoc}
     */
    public function labels(): TypedMap
    {
        return Labels::new(Wire\Type::BYTES)
            ->with(Labels::default, [])
            ->with(Labels::isEmpty, static fn (array $values): bool => [] === $values)
            ->with(Labels::serializeTag, false)
            ;
    }
}
