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

/**
 * @internal
 * @psalm-internal Prototype\Compiler
 */
final class Import implements \Stringable
{
    public const TYPE_DEFAULT = 0;
    public const TYPE_WEAK = 1;
    public const TYPE_PUBLIC = 2;

    /**
     * @param non-empty-string $path
     * @param self::TYPE_* $type
     */
    private function __construct(
        public readonly string $path,
        public readonly int $type,
    ) {}

    /**
     * @param non-empty-string $path
     */
    public static function default(string $path): self
    {
        return new self($path, self::TYPE_DEFAULT);
    }

    /**
     * @param non-empty-string $path
     */
    public static function weak(string $path): self
    {
        return new self($path, self::TYPE_WEAK);
    }

    /**
     * @param non-empty-string $path
     */
    public static function public(string $path): self
    {
        return new self($path, self::TYPE_PUBLIC);
    }

    /**
     * @return non-empty-string
     */
    public function __toString(): string
    {
        return $this->path;
    }
}
