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

use Kafkiansky\Binary\Buffer;
use Kafkiansky\Binary\Endianness;
use Prototype\Serializer\Serializer;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;

#[CoversClass(Serializer::class)]
final class SerializerTest extends TestCase
{
    #[DataProviderExternal(FixtureProvider::class, 'messages')]
    public function testDeserialize(string $resourcePath, object $message): void
    {
        /** @var false|non-empty-string $bin */
        $bin = file_get_contents(__DIR__ . '/Fixtures/' .$resourcePath);
        self::assertNotFalse($bin);

        $buffer = Buffer::fromString(
            $bin,
            Endianness::little(),
        );

        $serializer = new Serializer();
        self::assertEquals($message, $serializer->deserialize($buffer, $message::class));
        self::assertTrue($buffer->isEmpty());
    }

    #[DataProviderExternal(FixtureProvider::class, 'messages')]
    public function testSerialize(string $_, object $message): void
    {
        $serializer = new Serializer();
        $buffer = $serializer->serialize($message);
        self::assertEquals($message, $serializer->deserialize($buffer, $message::class));
        self::assertTrue($buffer->isEmpty());
    }
}
