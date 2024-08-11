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

namespace Prototype\Compiler;

/**
 * @api
 */
final class RecursiveFilesLocator
{
    private const PROTO_EXTENSION = 'proto';

    /**
     * @param non-empty-list<non-empty-string> $paths
     * @return iterable<ProtoFile>
     */
    public static function files(array $paths): iterable
    {
        foreach ($paths as $path) {
            if (!is_file($path) && !is_dir($path)) {
                throw new \RuntimeException(\sprintf('Path "%s" could not be resolved.', $path));
            }

            if (is_file($path)) {
                yield ProtoFile::fromPath($path);
            }

            if (is_dir($path)) {
                $iterator = new \RecursiveIteratorIterator(
                    new \RecursiveCallbackFilterIterator(
                        new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS | \FilesystemIterator::KEY_AS_PATHNAME),
                        static fn (\SplFileInfo $fileInfo): bool => $fileInfo->isFile() && self::PROTO_EXTENSION === $fileInfo->getExtension(),
                    ),
                );

                /** @var non-empty-string $path */
                foreach ($iterator as $path => $_) {
                    yield ProtoFile::fromPath($path);
                }
            }
        }
    }
}
