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

namespace Prototype\Compiler\Internal\Proto;

/**
 * @internal
 * @psalm-internal Prototype\Compiler
 */
final class PhpType
{
    /**
     * @param non-empty-string $nativeType
     * @param ?non-empty-string $phpDocType
     */
    private function __construct(
        public readonly string $nativeType,
        public readonly ?string $phpDocType = null,
    ) {}

    public function toPhpDoc(string $paramName): string
    {
        return null !== $this->phpDocType ?
            \sprintf('@param %s $%s', $this->phpDocType, $paramName)
            : ''
            ;
    }

    /**
     * @param non-empty-string $nativeType
     * @param ?non-empty-string $phpDocType
     */
    public static function scalar(string $nativeType, ?string $phpDocType = null): self
    {
        return new self($nativeType, $phpDocType);
    }

    public static function list(self $type): self
    {
        return new self(
            'array',
            'list<'.($type->phpDocType ?: $type->nativeType).'>',
        );
    }

    public static function map(self $keyType, self $valueType): self
    {
        return new self(
            'array',
            'array<'.($keyType->phpDocType ?: $keyType->nativeType).', '.($valueType->phpDocType ?: $valueType->nativeType).'>',
        );
    }

    public static function union(self ...$types): self
    {
        $requirePhpDoc = false;

        foreach ($types as $type) {
            if (null !== $type->phpDocType) {
                $requirePhpDoc = true;
                break;
            }
        }

        return new self(
            implode('|', array_map(static fn (self $type): string => $type->nativeType, $types)), // @phpstan-ignore-line
            $requirePhpDoc ? implode('|', array_map(static fn (self $type): string => $type->phpDocType ?: $type->nativeType, $types)) : null, // @phpstan-ignore-line
        );
    }
}
