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

namespace Prototype\Compiler\Internal\Ir;

use Antlr\Antlr4\Runtime\InputStream;
use Prototype\Compiler\Import\ImportResolver;
use Prototype\Compiler\Internal\Ir\Hook\Hooks;
use Prototype\Compiler\Internal\Ir\Trace\DependencyGraph;
use Prototype\Compiler\Internal\Parser as Generated;

/**
 * @internal
 * @psalm-internal Prototype\Compiler
 */
final class ProtoResolver
{
    private function __construct(
        private readonly Parser $parser,
        private readonly ImportResolver $imports,
        private readonly DependencyGraph $dependencies,
        private readonly Hooks $hooks,
    ) {}

    public static function build(
        ImportResolver $imports,
        Parser $parser = new Parser(),
        Hooks $hooks = new Hooks(),
    ): self {
        return new self(
            $parser,
            $imports,
            DependencyGraph::empty(),
            $hooks,
        );
    }

    /**
     * @param non-empty-string $path
     * @return iterable<non-empty-string, Proto>
     * @throws \LogicException
     */
    public function resolve(string $path, ?InputStream $stream = null): iterable
    {
        /** @psalm-var \SplQueue<array{non-empty-string, Generated\Context\ProtoContext}> $queue */
        $queue = new \SplQueue(); // @phpstan-ignore-line
        $queue->setIteratorMode(\SplDoublyLinkedList::IT_MODE_DELETE | \SplDoublyLinkedList::IT_MODE_FIFO);

        $queue->enqueue([$path, $this->parser->parse($stream ?: InputStream::fromPath($path))]);

        /** @var array<non-empty-string, Proto> $files */
        $files = [];

        foreach ($queue as [$path, $context]) {
            if (isset($files[$path])) {
                continue;
            }

            /** @var Proto $proto */
            $proto = $context->accept(new ProtoVisitor($this->hooks)); // @phpstan-ignore-line

            /** @var list<non-empty-string> $imports */
            $imports = [];

            /** @var Import $import */
            foreach ($context->accept(new ImportVisitor()) as $import) { // @phpstan-ignore-line
                foreach ($this->imports->resolve($import->path) as $file) {
                    $imports[] = $file->path;

                    $this->dependencies->trace($path, $file->path);
                    $queue->enqueue([$file->path, $this->parser->parse($file->stream)]);
                }
            }

            $files[$path] = $proto->withImports($imports);
        }

        $this->dependencies->validate();

        $this->hooks->afterProtoResolved($files);

        yield from $files;
    }
}
