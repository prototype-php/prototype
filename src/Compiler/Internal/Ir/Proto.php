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

use Prototype\Compiler\Internal\Naming;

/**
 * @internal
 * @psalm-internal Prototype\Compiler
 */
final class Proto
{
    private const PHP_NAMESPACE = 'php_namespace';

    /**
     * @param non-empty-string $packageName
     * @param array<non-empty-string, Definition> $definitions
     * @param Option[] $options
     * @param list<non-empty-string> $imports
     * @param Service[] $services
     */
    public function __construct(
        public readonly string $packageName,
        public readonly array $definitions = [],
        public readonly array $options = [],
        public readonly array $imports = [],
        public readonly array $services = [],
    ) {}

    /**
     * @param list<non-empty-string> $imports
     */
    public function withImports(array $imports): self
    {
        return new self(
            $this->packageName,
            $this->definitions,
            $this->options,
            $imports,
            $this->services,
        );
    }

    /**
     * @return non-empty-string
     */
    public function phpNamespace(): string
    {
        $phpNamespaceOption = array_filter(
            $this->options,
            static fn (Option $option): bool => self::PHP_NAMESPACE === $option->name,
        );

        return Naming\NamespaceLike::name($phpNamespaceOption[0]->value ?? $this->packageName);
    }

    /**
     * @param non-empty-string $type
     * @return ?non-empty-string
     */
    public function resolveType(string $type): ?string
    {
        foreach (explode('.', $type) as $part) {
            if (null !== ($definition = $this->definitions[$part] ?? null)) {
                return match (true) {
                    $definition instanceof Message => $definition->typeStorage()->resolveType($type),
                    $definition instanceof Enum => $definition->name,
                    default => null,
                };
            }
        }

        return null;
    }

    /**
     * @param non-empty-string $type
     */
    public function resolveDefinition(string $type): ?Definition
    {
        foreach (explode('.', $type) as $part) {
            if (null !== ($definition = $this->definitions[$part] ?? null)) {
                return $definition;
            }
        }

        return null;
    }
}
