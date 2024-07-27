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

namespace Prototype\Serializer\Internal\Reflection;

use Prototype\Serializer\Exception\TypeIsNotSupported;
use Prototype\Serializer\Internal\Type\BoolType;
use Prototype\Serializer\Internal\Type\NativeTypeToProtobufTypeConverter;
use Prototype\Serializer\PrototypeException;
use Typhoon\DeclarationId\NamedClassId;
use Typhoon\Type\ShapeElement;
use Typhoon\Type\Type;
use Typhoon\Type\Visitor\DefaultTypeVisitor;

/**
 * @internal
 * @psalm-internal Prototype\Serializer
 * @template-extends DefaultTypeVisitor<PropertyMarshaller>
 */
final class NativeTypeToPropertyMarshallerConverter extends DefaultTypeVisitor
{
    private readonly NativeTypeToProtobufTypeConverter $nativeTypeToProtobufTypeConverter;

    public function __construct()
    {
        $this->nativeTypeToProtobufTypeConverter = new NativeTypeToProtobufTypeConverter();
    }

    /**
     * {@inheritdoc}
     */
    public function list(Type $type, Type $valueType, array $elements): ArrayPropertyMarshaller
    {
        return new ArrayPropertyMarshaller($valueType->accept($this));
    }

    /**
     * {@inheritdoc}
     */
    public function array(Type $type, Type $keyType, Type $valueType, array $elements): PropertyMarshaller
    {
        /** @psalm-suppress MixedArgumentTypeCoercion */
        return match (true) {
            \count($elements) > 0 => new ArrayShapePropertyMarshaller(
                array_merge( // @phpstan-ignore-line
                    ...array_map(
                        fn (int|string $name, ShapeElement $element): array => /** @var array<non-empty-string, PropertyMarshaller<mixed>> */ [
                            (string) $name => $element->type->accept($this),
                        ],
                        array_keys($elements),
                        $elements,
                    ),
                ),
            ),
            $keyType->accept(new IsString()) && $valueType->accept(new IsMixed()) => new StructPropertyMarshaller(),
            default => new HashTablePropertyMarshaller(
                new ScalarPropertyMarshaller($keyType->accept($this->nativeTypeToProtobufTypeConverter)),
                $valueType->accept($this),
            ),
        };
    }

    /**
     * @throws PrototypeException
     */
    public function namedObject(Type $type, NamedClassId $classId, array $typeArguments): PropertyMarshaller
    {
        try {
            return new ScalarPropertyMarshaller($type->accept($this->nativeTypeToProtobufTypeConverter));
        } catch (\Throwable) {
            /** @psalm-suppress ArgumentTypeCoercion */
            return match (true) {
                enum_exists($classId->name) => new EnumPropertyMarshaller($classId->name),
                instanceOfDateTime($classId->name) => new DateTimePropertyMarshaller($classId->name),
                isClassOf($classId->name, \DateInterval::class) => new DateIntervalPropertyMarshaller(),
                class_exists($classId->name) => new ObjectPropertyMarshaller($classId->name),
                default => throw new TypeIsNotSupported($classId->name),
            };
        }
    }

    /**
     * {@inheritdoc}
     */
    public function union(Type $type, array $ofTypes): array
    {
        $hasBool = false;
        $propertyMarshallers = [];

        foreach ($ofTypes as $ofType) {
            if ($ofType->accept(new IsBool())) {
                // Special case to treat the true|false as a bool.
                $hasBool = true;
            } elseif (!$ofType->accept(new IsNull())) {
                $propertyMarshallers[] = $ofType->accept($this);
            }
        }

        if ($hasBool) {
            $propertyMarshallers[] = new ScalarPropertyMarshaller(new BoolType());
        }

        return $propertyMarshallers;
    }

    /**
     * {@inheritdoc}
     */
    protected function default(Type $type): ScalarPropertyMarshaller
    {
        return new ScalarPropertyMarshaller($type->accept($this->nativeTypeToProtobufTypeConverter));
    }
}
