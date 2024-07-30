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

use Prototype\Tests\Serializer\Fixtures\ProtobufMessage;

final class FixtureProvider
{
    /**
     * @var ?array<string, array{non-empty-string, object}>
     */
    private static ?array $classes = null;

    /**
     * @return array<string, array{non-empty-string, object}>
     */
    public static function messages(): array
    {
        return self::$classes ??= self::loadFromFile(__DIR__ . '/Fixtures/messages.php');
    }

    /**
     * @param non-empty-string $file
     * @return array<string, array{non-empty-string, object}>
     */
    private static function loadFromFile(string $file): array
    {
        $messages = [];

        $declaredClasses = get_declared_classes();

        /** @psalm-suppress UnresolvableInclude */
        require_once $file;

        foreach (array_diff(get_declared_classes(), $declaredClasses) as $class) {
            $reflectionClass = new \ReflectionClass($class);

            /** @psalm-suppress UndefinedClass */
            $attributes = $reflectionClass->getAttributes(ProtobufMessage::class);

            foreach ($attributes as $attribute) {
                $classAttribute = $attribute->newInstance();

                /** @psalm-suppress RedundantCast */
                $messages[$class.'::'.$classAttribute->constructorFunction.'/'.$classAttribute->path] = [
                    $classAttribute->path,
                    [$class, $classAttribute->constructorFunction](), // @phpstan-ignore-line
                ];
            }
        }

        /** @var array<string, array{non-empty-string, object}> */
        return $messages;
    }
}
