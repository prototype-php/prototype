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

use Prototype\Compiler\Internal\Code\DefinitionGenerator;
use Prototype\Compiler\Internal\Ir\Trace\TypeStorage;

/**
 * @internal
 * @psalm-internal Prototype\Compiler
 * @template-implements \IteratorAggregate<array-key, Field>
 */
final class Message implements
    Definition,
    \IteratorAggregate,
    \Countable
{
    /**
     * @param non-empty-string $name
     * @param Field[] $fields
     */
    public function __construct(
        public readonly string $name,
        private readonly TypeStorage $types,
        public readonly array $fields = [],
    ) {}

    public function typeStorage(): TypeStorage
    {
        return clone $this->types;
    }

    /**
     * {@inheritdoc}
     */
    public function generate(DefinitionGenerator $generator): string
    {
        return $generator->generateClass($this);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator(): \Traversable
    {
        yield from $this->fields;
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return \count($this->fields);
    }
}
