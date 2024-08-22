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
use Prototype\Compiler\RecursiveFilesLocator;

abstract class GrpcTestCase extends TestCase
{
    /** @var non-empty-string */
    private static string $baseDir = __DIR__.'/build';

    protected function setUp(): void
    {
        parent::setUp();

        mkdir(self::$baseDir);

        self::compile(self::$baseDir, [...RecursiveFilesLocator::files([__DIR__.'/proto'])]);
        self::requireAll(self::$baseDir, [
            'Test/Api/V1/TestControllerClient.php',
            'Test/Api/V1/TestControllerServer.php',
            'Test/Api/V1/TestControllerServerRegistrar.php',
            'Test/Api/V1/AddTaskRequest.php',
            'Test/Api/V1/AddTaskResponse.php',
            'Test/Api/V1/AddTaskResponseErrorType.php',
        ]);
    }

    /**
     * @param non-empty-string $baseDir
     * @param iterable<non-empty-string> $paths
     */
    private static function requireAll(string $baseDir, iterable $paths): void
    {
        foreach ($paths as $path) {
            if (!is_file($path = $baseDir.\DIRECTORY_SEPARATOR.$path)) {
                throw new \LogicException(\sprintf('"%s" is not a file.', $path));
            }

            require_once $path;
        }
    }

    /**
     * @param non-empty-string $dir
     * @param iterable<ProtoFile> $files
     */
    private static function compile(string $dir, iterable $files): void
    {
        Compiler::build(new FileWriter($dir), imports: new CombineImportResolver([VirtualImportResolver::build()]))
            ->compileAll($files)
        ;
    }
}
