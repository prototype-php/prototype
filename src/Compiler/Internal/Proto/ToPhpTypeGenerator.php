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

use Typhoon\DeclarationId\AnonymousClassId;
use Typhoon\DeclarationId\NamedClassId;
use Typhoon\Type\Type;
use Typhoon\Type\Visitor\DefaultTypeVisitor;
use function Typhoon\Type\stringify;

/**
 * @internal
 * @psalm-internal Prototype\Compiler
 * @template-extends DefaultTypeVisitor<PhpType>
 */
final class ToPhpTypeGenerator extends DefaultTypeVisitor
{
    /**
     * {@inheritdoc}
     */
    public function string(Type $type): mixed
    {
        return PhpType::scalar('string');
    }

    /**
     * {@inheritdoc}
     */
    public function list(Type $type, Type $valueType, array $elements): mixed
    {
        return PhpType::list($valueType->accept($this));
    }

    /**
     * {@inheritdoc}
     */
    public function array(Type $type, Type $keyType, Type $valueType, array $elements): mixed
    {
        return PhpType::array($keyType->accept($this), $valueType->accept($this));
    }

    /**
     * {@inheritdoc}
     */
    public function float(Type $type, Type $minType, Type $maxType): mixed
    {
        return PhpType::scalar('float');
    }

    /**
     * {@inheritdoc}
     */
    public function union(Type $type, array $ofTypes): mixed
    {
        $types = [];
        $hasBool = false;

        foreach ($ofTypes as $ofType) {
            if ($ofType->accept(new IsBool())) {
                $hasBool = true;
            } else {
                $types[] = $ofType->accept($this);
            }
        }

        if ($hasBool) {
            $types[] = PhpType::scalar('bool');
        }

        return PhpType::union(...$types);
    }

    /**
     * {@inheritdoc}
     */
    public function namedObject(Type $type, NamedClassId|AnonymousClassId $classId, array $typeArguments): mixed
    {
        $name = $classId->name ?: throw new \LogicException('Type expected.');

        return PhpType::scalar(
            match ($name) {
                'int32',
                'int64',
                'uint32',
                'uint64',
                'sint32',
                'sint64',
                'fixed32',
                'fixed64',
                'sfixed32',
                'sfixed64'                  => 'int',
                'double'                    => 'float',
                'bytes'                     => 'string',
                'google.protobuf.Timestamp' => '\DateTimeInterface',
                'google.protobuf.Duration'  => '\DateInterval',
                'google.protobuf.Struct'    => 'array',
                default                     => $name,
            },
            match ($name) {
                'string',
                'bool',
                'float',
                'google.protobuf.Timestamp',
                'google.protobuf.Duration'  => null,
                'google.protobuf.Struct'    => 'array<string, mixed>',
                default                     => $name,
            },
        );
    }

    /**
     * {@inheritdoc}
     */
    public function classConstant(Type $type, Type $classType, string $name): mixed
    {
        return $classType->accept($this);
    }

    /**
     * {@inheritdoc}
     */
    protected function default(Type $type): mixed
    {
        throw new \LogicException(\sprintf('Type "%s" is unexpected.', stringify($type)));
    }
}

/**
 * @template-extends DefaultTypeVisitor<bool>
 */
final class IsBool extends DefaultTypeVisitor
{
    /**
     * {@inheritdoc}
     */
    public function true(Type $type): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function false(Type $type): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function default(Type $type): bool
    {
        return false;
    }
}
