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

use Prototype\Compiler\Internal\Ir\ProtoType;
use Prototype\Compiler\Internal\Ir\Scalar;
use Prototype\Compiler\Internal\Ir\Trace\TypeStorage;
use Prototype\Compiler\Internal\Ir\TypeVisitor;

/**
 * @internal
 * @psalm-internal Prototype\Compiler
 * @template-implements TypeVisitor<PhpType>
 */
final class ResolveReferenceTypeVisitor implements TypeVisitor
{
    /**
     * @param TypeVisitor<PhpType> $fallback
     */
    public function __construct(
        private readonly TypeVisitor $fallback,
        private readonly TypeStorage $types,
    ) {}

    /**
     * {@inheritdoc}
     */
    public function scalar(ProtoType $type, Scalar $scalar): PhpType
    {
        return $this->fallback->scalar($type, $scalar);
    }

    /**
     * {@inheritdoc}
     */
    public function message(ProtoType $type, string $message): PhpType
    {
        if (null !== ($reference = $this->types->resolveType($message))) {
            return PhpType::class($reference);
        }

        return $this->fallback->message($type, $message);
    }

    /**
     * {@inheritdoc}
     */
    public function enum(ProtoType $type, string $enum): PhpType
    {
        if (null !== ($reference = $this->types->resolveType($enum))) {
            return PhpType::enum($reference);
        }

        return $this->fallback->enum($type, $enum);
    }

    /**
     * {@inheritdoc}
     */
    public function repeated(ProtoType $type, ProtoType $elementType): PhpType
    {
        return PhpType::list($elementType->accept($this));
    }

    /**
     * {@inheritdoc}
     */
    public function map(ProtoType $type, ProtoType $keyType, ProtoType $valueType): PhpType
    {
        return PhpType::array(
            $keyType->accept($this),
            $valueType->accept($this),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function oneof(ProtoType $type, array $variants): PhpType
    {
        return PhpType::union(
            array_map(
                fn (ProtoType $protoType): PhpType => $protoType->accept($this),
                $variants,
            ),
        );
    }
}
