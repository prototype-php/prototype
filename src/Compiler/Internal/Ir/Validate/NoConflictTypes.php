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

namespace Prototype\Compiler\Internal\Ir\Validate;

use Prototype\Compiler\Internal\Ir\Enum;
use Prototype\Compiler\Internal\Ir\Hook\AfterProtoResolvedHook;
use Prototype\Compiler\Internal\Ir\Message;

/**
 * @internal
 * @psalm-internal Prototype\Compiler
 */
final class NoConflictTypes implements AfterProtoResolvedHook
{
    /**
     * {@inheritdoc}
     */
    public function afterProtoResolved(iterable $files): void
    {
        /** @var array<non-empty-string, array<non-empty-string, bool>> $types */
        $types = [];

        foreach ($files as $path => $file) {
            $relativePath = implode(\DIRECTORY_SEPARATOR, \array_slice(explode(\DIRECTORY_SEPARATOR, $path), -3));

            if (!isset($types[$file->packageName])) {
                $types[$file->packageName] = [];
            }

            foreach ($file->definitions as $definition) {
                $name = match (true) {
                    $definition instanceof Enum => $definition->name,
                    $definition instanceof Message => $definition->name,
                    default => throw new \LogicException(
                        \sprintf('Unknown definition of type "%s" found.', $definition::class),
                    ),
                };

                // It is enough to check the uniqueness of the top-level definitions,
                // because the uniqueness of the inner types depends on them.
                if (isset($types[$file->packageName][$name])) {
                    throw new ConstraintViolated(
                        \sprintf('"%s.%s" is already defined in file "%s".',
                            $file->packageName,
                            $name,
                            $relativePath,
                        ),
                    );
                }

                $types[$file->packageName][$name] = true;
            }
        }
    }
}
