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

namespace Prototype\Compiler\Import;

use Prototype\Compiler\Internal\Code\WellKnown;

/**
 * @api
 */
final class VirtualImportResolver implements ImportResolver
{
    /**
     * @param array<non-empty-string, bool> $imports
     */
    private function __construct(
        private readonly array $imports,
    ) {}

    /**
     * @param list<non-empty-string> $imports
     */
    public static function build(array $imports = []): self
    {
        $paths = array_combine(
            $types = array_keys([...WellKnown::pathToType()]),
            array_fill(0, \count($types), true),
        );

        return new self(
            array_merge(
                $paths,
                array_combine(
                    $imports,
                    array_fill(0, \count($imports), true),
                ),
            ),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function canResolve(string $path): bool
    {
        return isset($this->imports[$path]);
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(string $path): iterable
    {
        return [];
    }
}
