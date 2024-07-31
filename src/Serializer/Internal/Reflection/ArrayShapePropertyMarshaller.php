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
use Prototype\Serializer\Exception\PropertyValueIsInvalid;
use Prototype\Serializer\Internal\Label\Labels;
use Prototype\Serializer\Internal\Wire;
use Typhoon\TypedMap\TypedMap;

/**
 * @internal
 * @psalm-internal Prototype\Serializer
 * @template T
 * @template-implements PropertyMarshaller<iterable<non-empty-string, T>>
 */
final class ArrayShapePropertyMarshaller implements PropertyMarshaller
{
    /** @var array<non-empty-string, PropertyMarshaller<T>>  */
    private readonly array $marshallers;

    /** @var array<positive-int, non-empty-string> */
    private readonly array $deserializerNums;

    /** @var array<non-empty-string, positive-int> */
    private readonly array $serializersNums;

    /**
     * @param array<non-empty-string, PropertyMarshaller<T>> $marshallers
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
    public function deserializeValue(Binary\Buffer $buffer, Deserializer $deserializer, Wire\Tag $tag): iterable
    {
        $buffer = $buffer->split($buffer->consumeVarUint());

        $values = [];

        while (!$buffer->isEmpty()) {
            $tag = Wire\Tag::decode($buffer);

            if (!isset($this->deserializerNums[$tag->num])) {
                Wire\discard($buffer, $tag);

                continue;
            }

            $fieldName = $this->deserializerNums[$tag->num];

            $value = $this->marshallers[$fieldName]->deserializeValue($buffer, $deserializer, $tag);

            if (\is_array($value)) {
                if (!isset($values[$fieldName]) || !\is_array($values[$fieldName])) {
                    $values[$fieldName] = [];
                }

                $value = array_merge($values[$fieldName], $value);
            }

            $values[$fieldName] = $value;
        }

        yield from $values; // @phpstan-ignore-line
    }

    /**
     * {@inheritdoc}
     */
    public function serializeValue(Binary\Buffer $buffer, Serializer $serializer, mixed $value, Wire\Tag $tag): void
    {
        $shapeBuffer = $buffer->clone();

        /** @psalm-suppress MixedAssignment */
        foreach ($value as $key => $val) {
            if (!isset($this->serializersNums[$key])) {
                throw new PropertyValueIsInvalid($key);
            }

            $num = $this->serializersNums[$key];

            $marshaller = $this->marshallers[$key];
            $labels = $marshaller->labels();

            $fieldTag = new Wire\Tag($num, $labels[Labels::wireType]);

            if ($labels[Labels::serializeTag]) {
                $fieldTag->encode($shapeBuffer);
            }

            $marshaller->serializeValue($shapeBuffer, $serializer, $val, $fieldTag);
        }

        if (!$shapeBuffer->isEmpty()) {
            $tag->encode($buffer);

            $buffer
                ->writeVarUint($shapeBuffer->count())
                ->write($shapeBuffer->reset())
            ;
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
