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

namespace Prototype\Compiler\Internal\Code\Type;

use Prototype\Compiler\Internal\Ir\ProtoType;
use Prototype\Compiler\Internal\Ir\Scalar;
use Prototype\Compiler\Internal\Ir\TypeVisitor;

/**
 * @internal
 * @psalm-internal Prototype\Compiler
 * @template-implements TypeVisitor<string>
 */
final class StringifyTypeVisitor implements TypeVisitor
{
    /**
     * {@inheritdoc}
     */
    public function scalar(ProtoType $type, Scalar $scalar): string
    {
        return $scalar->value;
    }

    /**
     * {@inheritdoc}
     */
    public function message(ProtoType $type, string $message): string
    {
        return $message;
    }

    /**
     * {@inheritdoc}
     */
    public function repeated(ProtoType $type, ProtoType $elementType): string
    {
        return \sprintf('repeated %s', $elementType->accept($this));
    }

    /**
     * {@inheritdoc}
     */
    public function map(ProtoType $type, ProtoType $keyType, ProtoType $valueType): string
    {
        return \sprintf('map<%s, %s>', $keyType->accept($this), $valueType->accept($this));
    }

    /**
     * {@inheritdoc}
     */
    public function oneof(ProtoType $type, array $variants): string
    {
        return implode('|', array_map(fn (ProtoType $variant): string => $variant->accept($this), $variants));
    }
}
