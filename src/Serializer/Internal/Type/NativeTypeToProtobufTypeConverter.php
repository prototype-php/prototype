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

namespace Prototype\Serializer\Internal\Type;

use Prototype\Serializer\Exception\TypeIsNotSupported;
use Prototype\Serializer\PrototypeException;
use Typhoon\DeclarationId\NamedClassId;
use Typhoon\Type\Type;
use Typhoon\Type\Visitor\DefaultTypeVisitor;
use function Typhoon\Type\stringify;

/**
 * @internal
 * @psalm-internal Prototype\Serializer
 * @template-extends DefaultTypeVisitor<TypeSerializer>
 */
final class NativeTypeToProtobufTypeConverter extends DefaultTypeVisitor
{
    /**
     * {@inheritdoc}
     */
    public function string(Type $type): StringType
    {
        return new StringType();
    }

    /**
     * {@inheritdoc}
     */
    public function mixed(Type $type): ValueType
    {
        return new ValueType();
    }

    /**
     * @throws PrototypeException
     * @throws \ReflectionException
     */
    public function namedObject(Type $type, NamedClassId $classId, array $typeArguments): TypeSerializer
    {
        if ($classId->reflect()->implementsInterface(TypeSerializer::class)) {
            /** @var TypeSerializer */
            return $classId->reflect()->newInstanceWithoutConstructor();
        }

        return $this->default($type);
    }

    /**
     * {@inheritdoc}
     */
    public function float(Type $type, ?float $min, ?float $max): FloatType|DoubleType
    {
        return match (true) {
            $min === -1.7976931348623157E+308 && $max === 1.7976931348623157E+308 => new DoubleType(),
            default => new FloatType(),
        };
    }

    /**
     * @throws TypeIsNotSupported
     */
    protected function default(Type $type): TypeSerializer
    {
        throw new TypeIsNotSupported(stringify($type));
    }
}
