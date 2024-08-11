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

namespace Prototype\Compiler\Internal\Ir\Trace;

/**
 * @internal
 * @psalm-internal Prototype\Compiler
 * @template-implements \IteratorAggregate<array-key, non-empty-string>
 */
final class DependencyGraph implements
    \IteratorAggregate,
    \Countable
{
    /** @var array<non-empty-string, non-empty-string[]> */
    private array $graph = [];

    /** @var array<non-empty-string, bool> */
    private array $visited = [];

    /** @var array<non-empty-string, bool> */
    private array $stack = [];

    private function __construct() {}

    public static function empty(): self
    {
        return new self();
    }

    /**
     * @param non-empty-string $file
     * @param non-empty-string ...$imports
     */
    public function trace(string $file, string ...$imports): void
    {
        $this->graph[$file] = array_merge($this->graph[$file] ?? [], $imports);
    }

    /**
     * @param non-empty-string ...$files
     * @throws \LogicException
     */
    public function validate(string ...$files): void
    {
        foreach ($files ?: $this as $file) {
            if (isset($this->stack[$file])) {
                throw new \LogicException(
                    \sprintf('File recursively imports itself: %s -> %s.', implode(' -> ', array_keys($this->stack)), $file),
                );
            }

            if (isset($this->visited[$file])) {
                continue;
            }

            $this->visited[$file] = $this->stack[$file] = true;

            foreach ($this->graph[$file] ?? [] as $import) {
                $this->validate($import);
            }

            unset($this->stack[$file]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator(): \Traversable
    {
        yield from array_keys($this->graph);
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return \count($this->graph);
    }
}
