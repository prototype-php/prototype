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
 * @template-implements PropertyMarshaller<iterable<non-empty-string, mixed>>
 */
final class ArrayShapePropertyMarshaller implements PropertyMarshaller
{
    /** @var array<non-empty-string, PropertyMarshaller<mixed>>  */
    private readonly array $marshallers;

    /** @var array<positive-int, non-empty-string> */
    private readonly array $deserializerNums;

    /** @var array<non-empty-string, positive-int> */
    private readonly array $serializersNums;

    /**
     * @param array<non-empty-string, PropertyMarshaller<mixed>> $marshallers
     */
    public function __construct(array $marshallers)
    {
        $this->marshallers = $marshallers;

        $fields = iterator_to_array(self::enumerate($marshallers));

        $this->deserializerNums = array_flip($fields);
        $this->serializersNums = $fields;
    }

    /**
     * {@inheritdoc}
     */
    public function deserializeValue(Byte\Reader $reader, Deserializer $deserializer, Wire\Tag $tag): iterable
    {
        $reader = $reader->slice();

        while ($reader->isNotEmpty()) {
            $tag = Wire\Tag::decode($reader);

            if (!isset($this->deserializerNums[$tag->num])) {
                Wire\discard($reader, $tag);

                continue;
            }

            $fieldName = $this->deserializerNums[$tag->num];

            yield $fieldName => $this->marshallers[$fieldName]->deserializeValue($reader, $deserializer, $tag);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function serializeValue(Byte\Writer $writer, Serializer $serializer, mixed $value, Wire\Tag $tag): void
    {
        $shapeBuffer = $writer->clone();

        /** @psalm-suppress MixedAssignment */
        foreach ($value as $key => $val) {
            $num = $this->serializersNums[$key];
            $fieldTag = new Wire\Tag($num, $this->marshallers[$key]->labels()[Labels::wireType]);
            $fieldTag->encode($shapeBuffer);
            $this->marshallers[$key]->serializeValue($shapeBuffer, $serializer, $val, $fieldTag);
        }

        if ($shapeBuffer->isNotEmpty()) {
            $tag->encode($writer);
            $writer->copyFrom($shapeBuffer);
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

    /**
     * @param array<non-empty-string, PropertyMarshaller<mixed>> $marshallers
     * @return \Generator<non-empty-string, positive-int>
     */
    private static function enumerate(array $marshallers): \Generator
    {
        $num = 0;

        foreach ($marshallers as $fieldName => $_) {
            yield $fieldName => ++$num;
        }
    }
}
