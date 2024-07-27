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

namespace Prototype\Tests\Serializer\Wire;

use Kafkiansky\Binary\Buffer;
use Kafkiansky\Binary\Endianness;
use Prototype\Serializer\Internal\Wire\Tag;
use Prototype\Serializer\Internal\Wire\Type;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Tag::class)]
final class TagTest extends TestCase
{
    /**
     * @return iterable<array-key, array{Tag}>
     */
    public static function fixtures(): iterable
    {
        yield Type::VARINT->name => [
            new Tag(1, Type::VARINT),
        ];

        yield Type::FIXED32->name => [
            new Tag(2, Type::FIXED32),
        ];

        yield Type::FIXED64->name => [
            new Tag(3, Type::FIXED64),
        ];

        yield Type::BYTES->name => [
            new Tag(4, Type::BYTES),
        ];
    }

    #[DataProvider('fixtures')]
    public function testEncodeDecode(Tag $tag): void
    {
        $buffer = Buffer::empty(Endianness::little());
        $tag->encode($buffer);
        self::assertEquals($tag, Tag::decode($buffer));
        self::assertCount(0, $buffer);
    }
}
