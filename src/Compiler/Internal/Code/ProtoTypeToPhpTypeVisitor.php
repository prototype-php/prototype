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

use Prototype\Compiler\Internal\Ir;
use Prototype\Compiler\Internal\Ir\ProtoType;
use Prototype\Compiler\Internal\Ir\Scalar;

/**
 * @internal
 * @psalm-internal Prototype\Compiler
 * @template-implements Ir\TypeVisitor<PhpType>
 */
final class ProtoTypeToPhpTypeVisitor implements Ir\TypeVisitor
{
    /**
     * @var array<non-empty-string, PhpType>
     */
    private readonly array $wellKnowns;

    public function __construct()
    {
        $this->wellKnowns = [...WellKnown::typeToPhpType()];
    }

    /**
     * {@inheritdoc}
     */
    public function scalar(ProtoType $type, Scalar $scalar): PhpType
    {
        return match ($scalar) {
            Scalar::bool   => PhpType::scalar('bool'),
            Scalar::string => PhpType::scalar('string'),
            Scalar::bytes  => PhpType::scalar('string', 'bytes'),
            Scalar::double => PhpType::scalar('float', 'double'),
            Scalar::float  => PhpType::scalar('float'),
            default        => PhpType::scalar('int', $scalar->value),
        };
    }

    /**
     * {@inheritdoc}
     */
    public function message(ProtoType $type, string $message): PhpType
    {
        return $this->wellKnowns[$message] ?? PhpType::class($message);
    }

    /**
     * {@inheritdoc}
     */
    public function enum(ProtoType $type, string $enum): PhpType
    {
        return $this->wellKnowns[$enum] ?? PhpType::enum($enum);
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
