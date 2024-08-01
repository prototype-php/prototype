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

use Prototype\Serializer\Byte\Buffer;
use Kafkiansky\Binary\Endianness;
use Prototype\Serializer\Exception\EnumDoesNotContainVariant;
use Prototype\Serializer\Exception\EnumDoesNotContainZeroVariant;
use Prototype\Serializer\Exception\TypeIsNotSupported;
use Prototype\Serializer\Exception\TypeIsUnknown;
use Prototype\Serializer\Serializer;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;
use Prototype\Tests\Serializer\Fixtures\EmptyMessage;
use Prototype\Tests\Serializer\Fixtures\InvalidMessage;

#[CoversClass(Serializer::class)]
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
