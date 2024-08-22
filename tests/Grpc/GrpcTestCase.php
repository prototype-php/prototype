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

namespace Prototype\Tests\Grpc;

use PHPUnit\Framework\TestCase;
use Prototype\Compiler\Compiler;
use Prototype\Compiler\Import\CombineImportResolver;
use Prototype\Compiler\Import\VirtualImportResolver;
use Prototype\Compiler\Output\FileWriter;
use Prototype\Compiler\ProtoFile;

abstract class GrpcTestCase extends TestCase
{
    /** @var non-empty-string */
    private static string $baseDir = __DIR__.'/build';

    protected function setUp(): void
    {
        parent::setUp();

        mkdir(self::$baseDir);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $cleanDirectory = static function (string ...$paths): void {
            foreach ($paths as $path) {
                /** @var \SplFileInfo $file */
                foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST) as $file) {
                    $op = match (true) {
                        $file->isDir() => rmdir(...),
                        default => unlink(...),
                    };

                    $op($file->getRealPath());
                }

                rmdir($path);
            }
        };

        $cleanDirectory(self::$baseDir);
    }


    /**
     * @param iterable<non-empty-string> $paths
     */
    final protected static function requireAll(iterable $paths): void
    {
        foreach ($paths as $path) {
            if (!is_file($path = self::$baseDir.\DIRECTORY_SEPARATOR.$path)) {
                throw new \LogicException(\sprintf('"%s" is not a file.', $path));
            }

            require_once $path;
        }
    }

    /**
     * @param non-empty-string $proto
     */
    final protected static function compile(string $proto): void
    {
        Compiler::build(new FileWriter(self::$baseDir), imports: new CombineImportResolver([VirtualImportResolver::build()]))
            ->compile(ProtoFile::fromString($proto))
        ;
    }
}
