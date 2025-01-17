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

namespace Prototype\Compiler\Internal\Code;

use Prototype\Compiler\Internal\Naming;

/**
 * @internal
 * @psalm-internal Prototype\Compiler
 */
final class PhpType
{
    /**
     * @param non-empty-string $nativeType
     * @param ?non-empty-string $phpDocType
     * @param non-empty-string[] $uses
     */
    private function __construct(
        public readonly string $nativeType,
        public readonly ?string $phpDocType = null,
        public readonly array $uses = [],
        public readonly bool $nullable = false,
        public readonly mixed $default = null,
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
    public static function scalar(string $nativeType, ?string $phpDocType = null, null|bool|string|int $default = null): self
    {
        if (null !== $phpDocType) {
            $phpDocType = \sprintf('?%s', $phpDocType);
        }

        return new self($nativeType, $phpDocType, nullable: null === $default, default: $default);
    }

    /**
     * @param non-empty-string $class
     * @param ?non-empty-string $use
     */
    public static function class(string $class, ?string $use = null): self
    {
        return new self(
            Naming\ClassLike::name($class),
            uses: null !== $use ? [$use] : [],
            nullable: true,
        );
    }

    public static function list(self $type): self
    {
        return new self(
            'array',
            'list<'.($type->phpDocType ?: $type->nativeType).'>',
            uses: $type->uses,
            default: [],
        );
    }

    public static function array(self $keyType, self $valueType): self
    {
        return new self(
            'array',
            'array<'.($keyType->phpDocType ?: $keyType->nativeType).', '.($valueType->phpDocType ?: $valueType->nativeType).'>',
            uses: [...$keyType->uses, ...$valueType->uses],
            default: [],
        );
    }

    /**
     * @param self[] $types
     */
    public static function union(array $types): self
    {
        $types = [self::scalar('null'), ...$types];

        $requirePhpDoc = false;

        foreach ($types as $type) {
            if (null !== $type->phpDocType) {
                $requirePhpDoc = true;
                break;
            }
        }

        return new self(
            implode('|', array_map(static fn (self $type): string => $type->nativeType, $types)),
            $requirePhpDoc ? implode('|', array_map(static fn (self $type): string => $type->phpDocType ?: $type->nativeType, $types)) : null,
            uses: array_merge(
                ...array_map(
                    static fn (self $type): array => $type->uses,
                    $types,
                ),
            ),
        );
    }
}
