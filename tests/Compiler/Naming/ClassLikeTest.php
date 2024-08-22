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

namespace Prototype\Tests\Compiler\Naming;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Prototype\Compiler\Internal\Naming\BuiltinLike;
use Prototype\Compiler\Internal\Naming\ClassLike;

#[CoversClass(ClassLike::class)]
#[CoversClass(BuiltinLike::class)]
final class ClassLikeTest extends TestCase
{
    /**
     * @return iterable<array-key, array{non-empty-string, non-empty-string}>
     */
    public static function fixtures(): iterable
    {
        yield 'String_' => [
            'string',
            'String_',
        ];

        yield 'Class_' => [
            'class',
            'Class_',
        ];

        yield 'CALLABLE_' => [
            'CALLABLE',
            'CALLABLE_',
        ];

        yield 'RequireOnce' => [
            'require_once',
            'RequireOnce',
        ];

        yield '__FUNCTION__' => [
            '__FUNCTION__',
            'FUNCTION_',
        ];
    }

    /**
     * @param non-empty-string $name
     * @param non-empty-string $className
     */
    #[DataProvider('fixtures')]
    public function testClassName(string $name, string $className): void
    {
        self::assertSame($className, ClassLike::name($name));
    }
}
