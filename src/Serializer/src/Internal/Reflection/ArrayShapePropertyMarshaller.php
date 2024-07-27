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
use Kafkiansky\Prototype\Internal\Label\Labels;
use Kafkiansky\Prototype\Internal\Wire;
use Typhoon\TypedMap\TypedMap;

/**
 * @internal
 * @psalm-internal Kafkiansky\Prototype
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
        $this->deserializerNums = self::deserializersToNums($marshallers);
        $this->serializersNums = self::serializersToNums($marshallers);
    }

    /**
     * {@inheritdoc}
     */
    public function deserializeValue(Binary\Buffer $buffer, Deserializer $deserializer, Wire\Tag $tag): iterable
    {
        $buffer = $buffer->split($buffer->consumeVarUint());

        while (!$buffer->isEmpty()) {
            $tag = Wire\Tag::decode($buffer);

            if (!isset($this->deserializerNums[$tag->num])) {
                Wire\discard($buffer, $tag);

                continue;
            }

            $fieldName = $this->deserializerNums[$tag->num];

            yield $fieldName => $this->marshallers[$fieldName]->deserializeValue($buffer, $deserializer, $tag);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function serializeValue(Binary\Buffer $buffer, Serializer $serializer, mixed $value, Wire\Tag $tag): void
    {
        $tag->encode($buffer);

        $shapeBuffer = $buffer->clone();

        /** @psalm-suppress MixedAssignment */
        foreach ($value as $key => $val) {
            $num = $this->serializersNums[$key];
            $fieldTag = new Wire\Tag($num, $this->marshallers[$key]->labels()[Labels::wireType]);
            $fieldTag->encode($shapeBuffer);
            $this->marshallers[$key]->serializeValue($shapeBuffer, $serializer, $val, $fieldTag);
        }

        if (!$shapeBuffer->isEmpty()) {
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
            ;
    }

    /**
     * @param array<non-empty-string, PropertyMarshaller<mixed>> $deserializers
     * @return array<positive-int, non-empty-string>
     */
    private static function deserializersToNums(array $deserializers): array
    {
        [$nums, $num] = [[], 0];

        foreach ($deserializers as $fieldName => $_) {
            $nums[++$num] = $fieldName;
        }

        return $nums;
    }

    /**
     * @param array<non-empty-string, PropertyMarshaller<mixed>> $serializers
     * @return array<non-empty-string, positive-int>
     */
    private static function serializersToNums(array $serializers): array
    {
        [$nums, $num] = [[], 0];

        foreach ($serializers as $fieldName => $_) {
            $nums[$fieldName] = ++$num;
        }

        return $nums;
    }
}
