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
 */
final class TypeStorage
{
    /** @var ?non-empty-string  */
    private ?string $typename = null;

    /** @var array<non-empty-string, self>  */
    private array $relations = [];

    /**
     * @param non-empty-string $name
     */
    private function __construct(
        private readonly string $name,
        private readonly ?self $parent = null,
        private readonly bool $root = false,
    ) {
        $this->parent?->addRelation($this);
    }

    /**
     * @param non-empty-string $name
     */
    public static function root(string $name): self
    {
        return new self($name, root: true);
    }

    /**
     * @param non-empty-string $type
     */
    public function fork(string $type): self
    {
        return new self($type, $this);
    }

    /**
     * @return non-empty-string
     */
    public function typeName(): string
    {
        if (null !== $this->typename) {
            return $this->typename;
        }

        /** @var non-empty-list<non-empty-string> $typename */
        $typename = [];

        for ($parent = $this; $parent !== null; $parent = $parent->parent) {
            if (!$parent->root) {
                $typename[] = $parent->name;
            }
        }

        return $this->typename = implode('', array_reverse($typename));
    }

    /**
     * @param non-empty-string $type
     * @return ?non-empty-string
     */
    public function resolveType(string $type): ?string
    {
        if ($type === $this->name) {
            return $this->typeName();
        }

        if (isset($this->relations[$type])) {
            return $this->relations[$type]->typeName();
        }

        if (str_starts_with($type, $this->name)) {
            $type = substr($type, \strlen($this->name) + 1);

            if ('' === $type) {
                return null;
            }
        } else {
            for ($parent = $this->parent; $parent !== null; $parent = $parent->parent) {
                if (str_starts_with($type, $parent->name)) {
                    return $parent->resolveType($type);
                }
            }
        }

        return ($this->relations[explode('.', $type)[0]] ?? null)?->resolveType($type);
    }

    private function addRelation(self $relation): void
    {
        $this->relations[$relation->name] = $relation;
    }
}
