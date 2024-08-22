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

namespace Prototype\Tests\Serializer;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;
use Prototype\Byte\Buffer;
use Prototype\Serializer\Exception\EnumDoesNotContainVariant;
use Prototype\Serializer\Exception\EnumDoesNotContainZeroVariant;
use Prototype\Serializer\Exception\PropertyNumberIsInvalid;
use Prototype\Serializer\Exception\PropertyValueIsInvalid;
use Prototype\Serializer\Exception\TypeIsNotSupported;
use Prototype\Serializer\Exception\TypeIsUnknown;
use Prototype\Serializer\Exception\TypeWasNotExpected;
use Prototype\Serializer\Exception\ValueIsNotSerializable;
use Prototype\Serializer\Field;
use Prototype\Serializer\Internal\Label\LabelDefault;
use Prototype\Serializer\Internal\Label\LabelIsEmpty;
use Prototype\Serializer\Internal\Label\LabelPacked;
use Prototype\Serializer\Internal\Label\Labels;
use Prototype\Serializer\Internal\Label\LabelSerializeTag;
use Prototype\Serializer\Internal\Reflection\ArrayPropertyMarshaller;
use Prototype\Serializer\Internal\Reflection\ArrayShapePropertyMarshaller;
use Prototype\Serializer\Internal\Reflection\ConstantEnumPropertyMarshaller;
use Prototype\Serializer\Internal\Reflection\DateIntervalPropertyMarshaller;
use Prototype\Serializer\Internal\Reflection\DateTimePropertyMarshaller;
use Prototype\Serializer\Internal\Reflection\Deserializer;
use Prototype\Serializer\Internal\Reflection\EnumPropertyMarshaller;
use Prototype\Serializer\Internal\Reflection\HashTablePropertyMarshaller;
use Prototype\Serializer\Internal\Reflection\ObjectPropertyMarshaller;
use Prototype\Serializer\Internal\Reflection\PropertyDeserializeDescriptor;
use Prototype\Serializer\Internal\Reflection\PropertyMarshaller;
use Prototype\Serializer\Internal\Reflection\PropertySerializeDescriptor;
use Prototype\Serializer\Internal\Reflection\ProtobufReflector;
use Prototype\Serializer\Internal\Reflection\ScalarPropertyMarshaller;
use Prototype\Serializer\Internal\Reflection\StructPropertyMarshaller;
use Prototype\Serializer\Internal\Reflection\UnionPropertyMarshaller;
use Prototype\Serializer\Internal\Type\BoolType;
use Prototype\Serializer\Internal\Type\DoubleType;
use Prototype\Serializer\Internal\Type\DurationType;
use Prototype\Serializer\Internal\Type\Fixed32Type;
use Prototype\Serializer\Internal\Type\Fixed64Type;
use Prototype\Serializer\Internal\Type\FloatType;
use Prototype\Serializer\Internal\Type\ProtobufType;
use Prototype\Serializer\Internal\Type\SFixed32Type;
use Prototype\Serializer\Internal\Type\SFixed64Type;
use Prototype\Serializer\Internal\Type\SInt32Type;
use Prototype\Serializer\Internal\Type\SInt64Type;
use Prototype\Serializer\Internal\Type\StringType;
use Prototype\Serializer\Internal\Type\TimestampType;
use Prototype\Serializer\Internal\Type\ValueType;
use Prototype\Serializer\Internal\Type\VarintType;
use Prototype\Serializer\Internal\TypeConverter\ClassResolver;
use Prototype\Serializer\Internal\TypeConverter\IsBool;
use Prototype\Serializer\Internal\TypeConverter\IsConstantEnum;
use Prototype\Serializer\Internal\TypeConverter\IsMixed;
use Prototype\Serializer\Internal\TypeConverter\IsNull;
use Prototype\Serializer\Internal\TypeConverter\IsString;
use Prototype\Serializer\Internal\TypeConverter\NativeTypeToPropertyMarshallerConverter;
use Prototype\Serializer\Internal\TypeConverter\NativeTypeToProtobufTypeConverter;
use Prototype\Serializer\Internal\TypeConverter\ToFloatValue;
use Prototype\Serializer\Internal\TypeConverter\ToIntValue;
use Prototype\Serializer\Internal\Wire\ProtobufMarshaller;
use Prototype\Serializer\Internal\Wire\ValueContext;
use Prototype\Serializer\Serializer;
use Prototype\Tests\Serializer\Fixtures\EmptyMessage;
use Prototype\Tests\Serializer\Fixtures\InvalidMessage;
use Prototype\Tests\Serializer\Fixtures\MessageWithConstantEnum;
use Prototype\Tests\Serializer\Fixtures\MessageWithLiteralEnum;
use Prototype\Tests\Serializer\Fixtures\MessageWithTypeAliasEnum;

#[CoversClass(Serializer::class)]
#[CoversClass(ProtobufReflector::class)]
#[CoversClass(ArrayPropertyMarshaller::class)]
#[CoversClass(ArrayShapePropertyMarshaller::class)]
#[CoversClass(ConstantEnumPropertyMarshaller::class)]
#[CoversClass(DateIntervalPropertyMarshaller::class)]
#[CoversClass(DateTimePropertyMarshaller::class)]
#[CoversClass(Deserializer::class)]
#[CoversClass(EnumPropertyMarshaller::class)]
#[CoversClass(HashTablePropertyMarshaller::class)]
#[CoversClass(ObjectPropertyMarshaller::class)]
#[CoversClass(PropertyDeserializeDescriptor::class)]
#[CoversClass(PropertyMarshaller::class)]
#[CoversClass(PropertySerializeDescriptor::class)]
#[CoversClass(ScalarPropertyMarshaller::class)]
#[CoversClass(\Prototype\Serializer\Internal\Reflection\Serializer::class)]
#[CoversClass(StructPropertyMarshaller::class)]
#[CoversClass(UnionPropertyMarshaller::class)]
#[CoversClass(ClassResolver::class)]
#[CoversClass(ToFloatValue::class)]
#[CoversClass(ToIntValue::class)]
#[CoversClass(IsBool::class)]
#[CoversClass(IsConstantEnum::class)]
#[CoversClass(IsMixed::class)]
#[CoversClass(IsNull::class)]
#[CoversClass(IsString::class)]
#[CoversClass(NativeTypeToPropertyMarshallerConverter::class)]
#[CoversClass(NativeTypeToProtobufTypeConverter::class)]
#[CoversClass(ProtobufMarshaller::class)]
#[CoversClass(ValueContext::class)]
#[CoversClass(BoolType::class)]
#[CoversClass(FloatType::class)]
#[CoversClass(DoubleType::class)]
#[CoversClass(Fixed32Type::class)]
#[CoversClass(Fixed64Type::class)]
#[CoversClass(SFixed32Type::class)]
#[CoversClass(SFixed64Type::class)]
#[CoversClass(SInt32Type::class)]
#[CoversClass(SInt64Type::class)]
#[CoversClass(StringType::class)]
#[CoversClass(VarintType::class)]
#[CoversClass(ValueType::class)]
#[CoversClass(DurationType::class)]
#[CoversClass(TimestampType::class)]
#[CoversClass(LabelDefault::class)]
#[CoversClass(LabelIsEmpty::class)]
#[CoversClass(LabelPacked::class)]
#[CoversClass(LabelSerializeTag::class)]
#[CoversClass(Labels::class)]
#[CoversClass(Field::class)]
#[CoversClass(EnumDoesNotContainVariant::class)]
#[CoversClass(EnumDoesNotContainZeroVariant::class)]
#[CoversClass(PropertyNumberIsInvalid::class)]
#[CoversClass(PropertyValueIsInvalid::class)]
#[CoversClass(TypeIsNotSupported::class)]
#[CoversClass(TypeIsUnknown::class)]
#[CoversClass(TypeWasNotExpected::class)]
#[CoversClass(ValueIsNotSerializable::class)]
#[CoversClass(ProtobufType::class)]
#[CoversClass(Buffer::class)]
#[CoversFunction('\Prototype\Serializer\Internal\Reflection\isClassOf')]
#[CoversFunction('\Prototype\Serializer\Internal\Reflection\instanceOfDateTime')]
#[CoversFunction('\Prototype\Serializer\Internal\Wire\discard')]
final class SerializerTest extends TestCase
{
    #[DataProviderExternal(FixtureProvider::class, 'messages')]
    public function testDeserialize(string $resourcePath, object $message): void
    {
        /** @var false|non-empty-string $bin */
        $bin = file_get_contents(__DIR__ . '/Fixtures/' .$resourcePath);
        self::assertNotFalse($bin);

        $buffer = Buffer::fromString($bin);

        $serializer = new Serializer();
        self::assertEquals($message, $serializer->deserialize($buffer, $message::class));
        self::assertFalse($buffer->isNotEmpty());
    }

    #[DataProviderExternal(FixtureProvider::class, 'messages')]
    public function testSerialize(string $_, object $message): void
    {
        $serializer = new Serializer();
        $buffer = $serializer->serialize($message);
        self::assertEquals($message, $serializer->deserialize($buffer, $message::class));
        self::assertFalse($buffer->isNotEmpty());
    }

    public function testSerializeNotSupportedType(): void
    {
        $class = new class(1) {
            public function __construct(
                public readonly int $id,
            ) {}
        };

        $serializer = new Serializer();

        self::expectException(TypeIsNotSupported::class);
        self::expectExceptionMessage('The type "int" is not supported.');
        $serializer->serialize($class);
    }

    public function testDeserializeUnknownWireType(): void
    {
        $buffer = Buffer::default();
        $buffer = $buffer->writeVarint(1 << 3 | 300);

        $serializer = new Serializer();

        self::expectException(TypeIsUnknown::class);
        self::expectExceptionMessage('The field\'s "37" type "4" is unknown.');
        $serializer->deserialize($buffer, EmptyMessage::class);
    }

    public function testDeserializeEnumWithoutZeroVariant(): void
    {
        $serializer = new Serializer();

        self::expectException(EnumDoesNotContainZeroVariant::class);
        self::expectExceptionMessage('Enum "Prototype\Tests\Serializer\Fixtures\InvalidEnum" must contain zero variant.');
        $serializer->deserialize(Buffer::default(), InvalidMessage::class);
    }

    public function testDeserializeConstantEnumWithoutZeroVariant(): void
    {
        $serializer = new Serializer();

        self::expectException(EnumDoesNotContainZeroVariant::class);
        self::expectExceptionMessage('Enum "-1|1|2" must contain zero variant.');
        $serializer->deserialize(Buffer::default(), MessageWithConstantEnum::class);
    }

    public function testDeserializeLiteralEnumWithoutZeroVariant(): void
    {
        $serializer = new Serializer();

        self::expectException(EnumDoesNotContainZeroVariant::class);
        self::expectExceptionMessage('Enum "-1|1|2" must contain zero variant.');
        $serializer->deserialize(Buffer::default(), MessageWithLiteralEnum::class);
    }

    public function testDeserializeTypeAliasEnumWithoutZeroVariant(): void
    {
        $serializer = new Serializer();

        self::expectException(EnumDoesNotContainZeroVariant::class);
        self::expectExceptionMessage('Enum "CompressionTypeNone@Prototype\Tests\Serializer\Fixtures\MessageWithTypeAliasEnum|CompressionTypeGZIP@Prototype\Tests\Serializer\Fixtures\MessageWithTypeAliasEnum|CompressionTypeLZ4@Prototype\Tests\Serializer\Fixtures\MessageWithTypeAliasEnum" must contain zero variant.');
        $serializer->deserialize(Buffer::default(), MessageWithTypeAliasEnum::class);
    }

    public function testDeserializeEnumWithoutConcreteVariant(): void
    {
        $serializer = new Serializer();

        $buffer = Buffer::default();
        $buffer
            ->writeVarint(1 << 3 | 0)
            ->writeVarint(2)
        ;

        self::expectException(EnumDoesNotContainVariant::class);
        self::expectExceptionMessage('Enum "Prototype\Tests\Serializer\Fixtures\InvalidEnum" does not contain variant "2".');
        $serializer->deserialize($buffer, InvalidMessage::class);
    }
}
