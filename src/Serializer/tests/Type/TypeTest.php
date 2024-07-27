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

namespace Kafkiansky\Prototype\Tests\Type;

use Kafkiansky\Binary\Buffer;
use Kafkiansky\Binary\Endianness;
use Kafkiansky\Prototype\Internal\Type\BoolType;
use Kafkiansky\Prototype\Internal\Type\DoubleType;
use Kafkiansky\Prototype\Internal\Type\FixedInt32Type;
use Kafkiansky\Prototype\Internal\Type\FixedInt64Type;
use Kafkiansky\Prototype\Internal\Type\FixedUint32Type;
use Kafkiansky\Prototype\Internal\Type\FixedUint64Type;
use Kafkiansky\Prototype\Internal\Type\FloatType;
use Kafkiansky\Prototype\Internal\Type\StringType;
use Kafkiansky\Prototype\Internal\Type\TypeSerializer;
use Kafkiansky\Prototype\Internal\Type\VarintType;
use Kafkiansky\Prototype\Internal\Type\VaruintType;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(BoolType::class)]
#[CoversClass(FloatType::class)]
#[CoversClass(DoubleType::class)]
#[CoversClass(FixedInt32Type::class)]
#[CoversClass(FixedUint32Type::class)]
#[CoversClass(FixedInt64Type::class)]
#[CoversClass(FixedUint64Type::class)]
#[CoversClass(StringType::class)]
#[CoversClass(VarintType::class)]
#[CoversClass(VaruintType::class)]
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

        yield 'varuint' => [
            new VaruintType(),
            100,
        ];

        yield 'varint' => [
            new VarintType(),
            -1024,
        ];

        yield 'float' => [
            new FloatType(),
            2.5,
        ];

        yield 'double' => [
            new DoubleType(),
            10.25,
        ];

        yield 'string' => [
            new StringType(),
            'String',
        ];

        yield 'fixed32' => [
            new FixedUint32Type(),
            200,
        ];

        yield 'sfixed32' => [
            new FixedInt32Type(),
            -200,
        ];

        yield 'fixed64' => [
            new FixedUint64Type(),
            2048,
        ];

        yield 'sfixed64' => [
            new FixedInt64Type(),
            -2048,
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
        $buffer = Buffer::empty(Endianness::little());
        $type->writeTo($buffer, $value);
        self::assertSame($value, $type->readFrom($buffer));
        self::assertTrue($buffer->isEmpty());
    }
}
