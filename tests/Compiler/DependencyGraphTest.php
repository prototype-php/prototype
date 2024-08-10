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
use PHPUnit\Framework\TestCase;
use Prototype\Compiler\Internal\Import\DependencyGraph;

#[CoversClass(DependencyGraph::class)]
final class DependencyGraphTest extends TestCase
{
    public function testNoRecursiveDependencies(): void
    {
        $graph = DependencyGraph::empty();
        $graph->trace('A.proto', 'B.proto');
        $graph->trace('B.proto', 'C.proto', 'D.proto');
        $graph->trace('D.proto', 'X.proto');
        $graph->validate();
        self::assertTrue(true); // @phpstan-ignore-line
    }

    public function testRecursiveDependencies(): void
    {
        $graph = DependencyGraph::empty();
        $graph->trace('A.proto', 'B.proto');
        $graph->trace('B.proto', 'A.proto');
        self::expectException(\LogicException::class);
        self::expectExceptionMessage('File recursively imports itself: A.proto -> B.proto -> A.proto.');
        $graph->validate();
    }

    public function testTransitiveRecursiveDependencies(): void
    {
        $graph = DependencyGraph::empty();
        $graph->trace('A.proto', 'B.proto');
        $graph->trace('B.proto', 'C.proto');
        $graph->trace('C.proto', 'A.proto');
        self::expectException(\LogicException::class);
        self::expectExceptionMessage('File recursively imports itself: A.proto -> B.proto -> C.proto -> A.proto.');
        $graph->validate();
    }

    public function testRecursiveItselfDependencies(): void
    {
        $graph = DependencyGraph::empty();
        $graph->trace('A.proto', 'A.proto');
        self::expectException(\LogicException::class);
        self::expectExceptionMessage('File recursively imports itself: A.proto -> A.proto.');
        $graph->validate();
    }
}
