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

namespace Prototype\Tests\Compiler;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Prototype\Compiler\Compiler;
use Prototype\Compiler\Locator\FilesLocator;
use Prototype\Compiler\Locator\ProtoFile;
use Prototype\Compiler\Output\PhpFile;
use Prototype\Compiler\Output\Writer;

#[CoversClass(Compiler::class)]
final class CompilerTest extends TestCase
{
    /**
     * @return iterable<array-key, array{non-empty-string, PhpFile}>
     */
    public static function fixtures(): iterable
    {
        yield 'scalars' => [
            <<<'PROTO'
syntax = "proto3";

package api.v1.scheduler;

option php_namespace = "App\\Scheduler";

message ScheduleTaskRequest {
    string id = 1;
    string type = 2;
    fixed32 epoch = 3;
}
PROTO,
            new PhpFile(
                'ScheduleTaskRequest.php',
                <<<'PHP'
<?php

/**
 * This code was auto-generated by the prototype/compiler.
 * Do not edit this file manually.
 */

declare(strict_types=1);

namespace App\Scheduler;

/**
 * @api
 */
final class ScheduleTaskRequest
{
    /**
     * @param fixed32 $epoch
     */
    public function __construct(
        public readonly string $id,
        public readonly string $type,
        public readonly int $epoch,
    ) {
    }
}

PHP,
            ),
        ];
    }

    /**
     * @param non-empty-string $protobuf
     */
    #[DataProvider('fixtures')]
    public function testCompile(string $protobuf, PhpFile $phpFile): void
    {
        $writer = $this->createMock(Writer::class);
        $writer
            ->expects(self::once())
            ->method('writePhpFile')
            ->with($phpFile)
        ;

        $locator = $this->createMock(FilesLocator::class);
        $locator
            ->expects(self::once())
            ->method('files')
            ->willReturn([
                ProtoFile::fromString($protobuf),
            ])
        ;

        $compiler = Compiler::buildDefault();
        $compiler->compile($locator, $writer);
    }
}
