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

namespace Prototype\Serializer\Internal\TypeConverter;

use Prototype\Serializer\Exception\TypeIsNotSupported;
use Typhoon\DeclarationId\AnonymousClassId;
use Typhoon\DeclarationId\NamedClassId;
use Typhoon\Type\Type;
use Typhoon\Type\Visitor\DefaultTypeVisitor;
use function Typhoon\Type\stringify;

/**
 * @internal
 * @psalm-internal Prototype\Serializer
 * @template-extends DefaultTypeVisitor<NamedClassId|AnonymousClassId>
 */
final class ClassResolver extends DefaultTypeVisitor
{
    /**
     * {@inheritdoc}
     */
    public function namedObject(Type $type, NamedClassId|AnonymousClassId $classId, array $typeArguments): mixed
    {
        return $classId;
    }

    /**
     * {@inheritdoc}
     */
    public function self(Type $type, array $typeArguments, NamedClassId|AnonymousClassId|null $resolvedClassId): mixed
    {
        return $resolvedClassId ?? $this->default($type);
    }

    /**
     * {@inheritdoc}
     */
    public function parent(Type $type, array $typeArguments, NamedClassId|AnonymousClassId|null $resolvedClassId): mixed
    {
        return $resolvedClassId ?? $this->default($type);
    }

    /**
     * {@inheritdoc}
     */
    public function static(Type $type, array $typeArguments, NamedClassId|AnonymousClassId|null $resolvedClassId): mixed
    {
        return $resolvedClassId ?? $this->default($type);
    }

    /**
     * {@inheritdoc}
     */
    protected function default(Type $type): mixed
    {
        throw new TypeIsNotSupported(stringify($type));
    }
}
