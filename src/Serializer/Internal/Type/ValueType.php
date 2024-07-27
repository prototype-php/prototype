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

namespace Prototype\Serializer\Internal\Type;

use Kafkiansky\Binary;
use Prototype\Serializer\Exception\PropertyNumberIsInvalid;
use Prototype\Serializer\Exception\TypeWasNotExpected;
use Prototype\Serializer\Exception\ValueIsNotSerializable;
use Prototype\Serializer\Internal\Label\Labels;
use Prototype\Serializer\Internal\Wire\Tag;
use Prototype\Serializer\Internal\Wire\Type;
use Prototype\Serializer\PrototypeException;
use Typhoon\TypedMap\TypedMap;

/**
 * @internal
 * @psalm-internal Prototype\Serializer
 * @psalm-type ScalarValue = string|double|bool|null
 * @psalm-type StructValue = array<string, ScalarValue>
 * @psalm-type Value       = ScalarValue|StructValue
 * @psalm-type ListValue   = Value[]
 * @psalm-type JSONValue   = Value|StructValue|ListValue
 * @template-implements TypeSerializer<array<string, JSONValue>>
 */
final class ValueType implements TypeSerializer
{
    private const NULL_TYPE   = 1;
    private const NUMBER_TYPE = 2;
    private const STRING_TYPE = 3;
    private const BOOL_TYPE   = 4;
    private const STRUCT_TYPE = 5;
    private const LIST_TYPE   = 6;

    /**
     * @var array{
     *     1: callable(Binary\Buffer): null,
     *     2: callable(Binary\Buffer): double,
     *     3: callable(Binary\Buffer): string,
     *     4: callable(Binary\Buffer): bool,
     *     5: callable(Binary\Buffer): array<string, JSONValue>,
     *     6: callable(Binary\Buffer): JSONValue[],
     * }
     */
    private readonly array $readers;

    /**
     * @var array{
     *     1: callable(Binary\Buffer, null): void,
     *     2: callable(Binary\Buffer, double): void,
     *     3: callable(Binary\Buffer, string): void,
     *     4: callable(Binary\Buffer, bool): void,
     *     5: callable(Binary\Buffer, array<string, JSONValue>): void,
     *     6: callable(Binary\Buffer, JSONValue[]): void,
     * }
     */
    private readonly array $writers;

    public function __construct()
    {
        [$this->readers, $this->writers] = [
            [
                self::NULL_TYPE   => $this->readNull(...),
                self::NUMBER_TYPE => $this->readNumber(...),
                self::STRING_TYPE => $this->readString(...),
                self::BOOL_TYPE   => $this->readBool(...),
                self::STRUCT_TYPE => $this->readStruct(...),
                self::LIST_TYPE   => $this->readList(...),
            ],
            [
                self::NULL_TYPE   => $this->writeNull(...),
                self::NUMBER_TYPE => $this->writeNumber(...),
                self::STRING_TYPE => $this->writeString(...),
                self::BOOL_TYPE   => $this->writeBool(...),
                self::STRUCT_TYPE => $this->writeStruct(...),
                self::LIST_TYPE   => $this->writeList(...),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function readFrom(Binary\Buffer $buffer): array
    {
        $values = [];

        while (!$buffer->isEmpty()) {
            // Tag for google.protobuf.Value. Always BYTES.
            if (($type = Tag::decode($buffer)->type) !== Type::BYTES) {
                throw new TypeWasNotExpected($type->name);
            }

            // Single key pair of map<string, google.protobuf.Value>.
            $mapKeyValueBuffer = $buffer->split($buffer->consumeVarUint());

            // Tag for key from map<string, google.protobuf.Value>. Always BYTES (string).
            if (($type = Tag::decode($mapKeyValueBuffer)->type) !== Type::BYTES) {
                throw new TypeWasNotExpected($type->name);
            }

            $key = $mapKeyValueBuffer->consume($mapKeyValueBuffer->consumeVarUint());

            $values[$key] = $this->readValue($mapKeyValueBuffer);
        }

        return $values;
    }

    /**
     * {@inheritdoc}
     */
    public function writeTo(Binary\Buffer $buffer, mixed $value): void
    {
        foreach ($value as $key => $val) {
            $tag = new Tag(1, Type::BYTES);
            $tag->encode($buffer);

            $mapKeyValueBuffer = $buffer->clone();
            $tag->encode($mapKeyValueBuffer);

            $mapKeyValueBuffer->writeVarUint(\strlen($key))->write($key);
            $this->writeValue($mapKeyValueBuffer, $val);

            $buffer
                ->writeVarUint($mapKeyValueBuffer->count())
                ->write($mapKeyValueBuffer->reset())
            ;
        }
    }

    public function labels(): TypedMap
    {
        return Labels::new(Type::BYTES)
            ->with(Labels::default, [])
            ;
    }

    /**
     * @return null
     * @throws Binary\BinaryException
     * @throws PrototypeException
     */
    private function readNull(Binary\Buffer $buffer): mixed
    {
        // Null values always zero.
        (new VaruintType())->readFrom($buffer);

        return null;
    }

    /**
     * @psalm-param null $_
     * @throws Binary\BinaryException
     * @throws PrototypeException
     */
    private function writeNull(Binary\Buffer $buffer, mixed $_): void
    {
        (new VaruintType())->writeTo($buffer, 0);
    }

    /**
     * @throws Binary\BinaryException
     * @throws PrototypeException
     */
    private function readNumber(Binary\Buffer $buffer): float
    {
        return (new DoubleType())->readFrom($buffer);
    }

    /**
     * @param double $value
     * @throws Binary\BinaryException
     * @throws PrototypeException
     */
    private function writeNumber(Binary\Buffer $buffer, mixed $value): void
    {
        (new DoubleType())->writeTo($buffer, $value);
    }

    /**
     * @throws Binary\BinaryException
     * @throws PrototypeException
     */
    private function readString(Binary\Buffer $buffer): string
    {
        return (new StringType())->readFrom($buffer);
    }

    /**
     * @throws Binary\BinaryException
     * @throws PrototypeException
     */
    private function writeString(Binary\Buffer $buffer, string $value): void
    {
        (new StringType())->writeTo($buffer, $value);
    }

    /**
     * @throws Binary\BinaryException
     * @throws PrototypeException
     */
    private function readBool(Binary\Buffer $buffer): bool
    {
        return (new BoolType())->readFrom($buffer);
    }

    /**
     * @throws Binary\BinaryException
     * @throws PrototypeException
     */
    private function writeBool(Binary\Buffer $buffer, bool $value): void
    {
        (new BoolType())->writeTo($buffer, $value);
    }

    /**
     * @return JSONValue[]
     * @throws Binary\BinaryException
     * @throws PrototypeException
     */
    private function readList(Binary\Buffer $buffer): array
    {
        $buffer = $buffer->split($buffer->consumeVarUint());

        $list = [];

        while (!$buffer->isEmpty()) {
            $list[] = $this->readValue($buffer);
        }

        return $list;
    }

    /**
     * @param JSONValue[] $value
     * @throws Binary\BinaryException
     * @throws PrototypeException
     */
    private function writeList(Binary\Buffer $buffer, array $value): void
    {
        $list = $buffer->clone();

        foreach ($value as $element) {
            // When we are inside the list, the tag number will be 1, as specified in `google.protobuf.Struct`.
            $this->writeValue($list, $element, 1);
        }

        $buffer->writeVarUint($list->count())->write($list->reset());
    }

    /**
     * @return array<string, JSONValue>
     * @throws Binary\BinaryException
     * @throws PrototypeException
     */
    private function readStruct(Binary\Buffer $buffer): array
    {
        return $this->readFrom(
            $buffer->split($buffer->consumeVarUint()),
        );
    }

    /**
     * @param array<string, JSONValue> $value
     * @throws Binary\BinaryException
     * @throws PrototypeException
     */
    private function writeStruct(Binary\Buffer $buffer, array $value): void
    {
        $this->writeTo($struct = $buffer->clone(), $value);

        if (!$struct->isEmpty()) {
            $buffer->writeVarUint($struct->count())->write($struct->reset());
        }
    }

    /**
     * @return JSONValue
     * @throws Binary\BinaryException
     * @throws PrototypeException
     */
    private function readValue(Binary\Buffer $buffer): mixed
    {
        // Tag for google.protobuf.Value. Always BYTES.
        if (($type = Tag::decode($buffer)->type) !== Type::BYTES) {
            throw new TypeWasNotExpected($type->name);
        }

        // Value as google.protobuf.Value.
        $valueBuffer = $buffer->split($buffer->consumeVarUint());
        $valueBufferTag = Tag::decode($valueBuffer);

        if ($valueBufferTag->num > self::LIST_TYPE) {
            throw new PropertyNumberIsInvalid($valueBufferTag->num);
        }

        /** @var JSONValue */
        return $this->readers[$valueBufferTag->num]($valueBuffer);
    }

    /**
     * @param positive-int $tagNum
     * @param JSONValue $value
     * @throws Binary\BinaryException
     * @throws PrototypeException
     */
    private function writeValue(Binary\Buffer $buffer, mixed $value, int $tagNum = 2): void
    {
        /** @psalm-suppress DocblockTypeContradiction */
        $num = match (true) {
            null === $value                            => self::NULL_TYPE,
            \is_string($value)                         => self::STRING_TYPE,
            \is_bool($value)                           => self::BOOL_TYPE,
            \is_float($value) || \is_int($value)       => self::NUMBER_TYPE,
            \is_array($value) && array_is_list($value) => self::LIST_TYPE,
            \is_array($value)                          => self::STRUCT_TYPE,
            default                                    => throw new ValueIsNotSerializable($value, get_debug_type($value)),
        };

        $keyValueTag = new Tag($tagNum, Type::BYTES);
        $keyValueTag->encode($buffer);

        // An empty buffer for tagged value.
        $valueBuffer = $buffer->clone();

        $valueTag = new Tag($num, match ($num) {
            self::NULL_TYPE, self::BOOL_TYPE => Type::VARINT,
            self::NUMBER_TYPE                => Type::FIXED64,
            default                          => Type::BYTES,
        });
        $valueTag->encode($valueBuffer);
        /** @psalm-suppress InvalidArgument It is false positive here. */
        $this->writers[$num]($valueBuffer, $value);

        $buffer->writeVarUint($valueBuffer->count())->write($valueBuffer->reset());
    }
}
