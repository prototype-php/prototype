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

namespace Prototype\Tests\Serializer\Type;

use Prototype\Serializer\Byte\Buffer;
use Prototype\Serializer\Internal\Type\BoolType;
use Prototype\Serializer\Internal\Type\DoubleType;
use Prototype\Serializer\Internal\Type\SFixed32Type;
use Prototype\Serializer\Internal\Type\SFixed64Type;
use Prototype\Serializer\Internal\Type\Fixed32Type;
use Prototype\Serializer\Internal\Type\Fixed64Type;
use Prototype\Serializer\Internal\Type\FloatType;
use Prototype\Serializer\Internal\Type\SInt32Type;
use Prototype\Serializer\Internal\Type\SInt64Type;
use Prototype\Serializer\Internal\Type\StringType;
use Prototype\Serializer\Internal\Type\TypeSerializer;
use Prototype\Serializer\Internal\Type\VarintType;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

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
final class TypeTest extends TestCase
{
    /**
     * @return iterable<array-key, array{TypeSerializer, mixed}>
     */
    public static function fixtures(): iterable
    {
        yield 'true' => [
            new BoolType(),
            true,
        ];

        yield 'false' => [
            new BoolType(),
            false,
        ];

        yield 'varint/positive' => [
            new VarintType(),
            10023,
        ];

        yield 'varint/negative' => [
            new VarintType(),
            -10023,
        ];

        yield 'float/negative' => [
            new FloatType(),
            -10.25,
        ];

        yield 'float/positive' => [
            new FloatType(),
            10.25,
        ];

        yield 'double/positive' => [
            new DoubleType(),
            100.25,
        ];

        yield 'double/negative' => [
            new DoubleType(),
            -100.25,
        ];

        yield 'string' => [
            new StringType(),
            'String',
        ];

        yield 'fixed32' => [
            new Fixed32Type(),
            200,
        ];

        yield 'sfixed32/positive' => [
            new SFixed32Type(),
            10023,
        ];

        yield 'sfixed32/negative' => [
            new SFixed32Type(),
            -10023,
        ];

        yield 'fixed64' => [
            new Fixed64Type(),
            2048,
        ];

        yield 'sfixed64/positive' => [
            new SFixed64Type(),
            10023,
        ];

        yield 'sfixed64/negative' => [
            new SFixed64Type(),
            -10023,
        ];

        yield 'sint32/positive' => [
            new SInt32Type(),
            10023,
        ];

        yield 'sint32/negative' => [
            new SInt32Type(),
            -10023,
        ];

        yield 'sint64/negative' => [
            new SInt64Type(),
            -10023,
        ];

        yield 'sint64/positive' => [
            new SInt64Type(),
            10023,
        ];
    }

    /**
     * @template T
     * @param T $value
     * @param TypeSerializer<T> $type
     */
    #[DataProvider('fixtures')]
    public function testTypeRead(TypeSerializer $type, mixed $value): void
    {
        $buffer = Buffer::default();
        $type->writeTo($buffer, $value);
        self::assertSame($value, $type->readFrom($buffer));
        self::assertFalse($buffer->isNotEmpty());
    }
}
