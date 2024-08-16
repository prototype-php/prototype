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

use Prototype\Compiler\Exception\TypeNotFound;
use Prototype\Compiler\Internal\Code\PhpType;
use Prototype\Compiler\Internal\Ir\ProtoType;
use Prototype\Compiler\Internal\Ir\Scalar;
use Prototype\Compiler\Internal\Ir\TypeVisitor;

/**
 * @internal
 * @psalm-internal Prototype\Compiler
 * @template-implements TypeVisitor<PhpType>
 */
final class CombinedTypeVisitor implements TypeVisitor
{
    /** @var iterable<TypeVisitor<PhpType>>  */
    private readonly iterable $visitors;

    /**
     * @param TypeVisitor<PhpType> ...$visitors
     */
    public function __construct(
        TypeVisitor ...$visitors,
    ) {
        $this->visitors = $visitors;
    }

    /**
     * {@inheritdoc}
     */
    public function scalar(ProtoType $type, Scalar $scalar): PhpType
    {
        return $this->apply(
            static fn (TypeVisitor $visitor): PhpType => $visitor->scalar($type, $scalar),
            $type,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function message(ProtoType $type, string $message): PhpType
    {
        return $this->apply(
            static fn (TypeVisitor $visitor): PhpType => $visitor->message($type, $message),
            $type,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function repeated(ProtoType $type, ProtoType $elementType): PhpType
    {
        return PhpType::list(
            $this->apply(
                static fn (TypeVisitor $visitor): PhpType => $elementType->accept($visitor),
                $type,
            ),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function map(ProtoType $type, ProtoType $keyType, ProtoType $valueType): PhpType
    {
        return PhpType::array(
            $this->apply(
                static fn (TypeVisitor $visitor): PhpType => $keyType->accept($visitor),
                $keyType,
            ),
            $this->apply(
                static fn (TypeVisitor $visitor): PhpType => $valueType->accept($visitor),
                $valueType,
            ),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function oneof(ProtoType $type, array $variants): PhpType
    {
        return $this->apply(
            static fn (TypeVisitor $visitor): PhpType => $visitor->oneof($type, $variants),
            $type,
        );
    }

    /**
     * @param callable(TypeVisitor<PhpType>): PhpType $apply
     * @throws TypeNotFound
     */
    private function apply(callable $apply, ProtoType $type): PhpType
    {
        foreach ($this->visitors as $visitor) {
            try {
                return $apply($visitor);
            } catch (TypeNotFound) {}
        }

        throw new TypeNotFound($type->accept(new StringifyTypeVisitor()));
    }
}
