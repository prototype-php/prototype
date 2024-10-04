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
use Prototype\Serializer\Internal\Reflection\ArrayPropertyMarshaller;
use Prototype\Serializer\Internal\Reflection\ArrayShapePropertyMarshaller;
use Prototype\Serializer\Internal\Reflection\ConstantEnumPropertyMarshaller;
use Prototype\Serializer\Internal\Reflection\DateIntervalPropertyMarshaller;
use Prototype\Serializer\Internal\Reflection\DateTimePropertyMarshaller;
use Prototype\Serializer\Internal\Reflection\EnumPropertyMarshaller;
use Prototype\Serializer\Internal\Reflection\HashTablePropertyMarshaller;
use Prototype\Serializer\Internal\Reflection\ObjectPropertyMarshaller;
use Prototype\Serializer\Internal\Reflection\PropertyMarshaller;
use Prototype\Serializer\Internal\Reflection\ScalarPropertyMarshaller;
use Prototype\Serializer\Internal\Reflection\StructPropertyMarshaller;
use Prototype\Serializer\Internal\Type\BoolType;
use Prototype\Serializer\PrototypeException;
use Typhoon\DeclarationId\AliasId;
use Typhoon\DeclarationId\AnonymousClassId;
use Typhoon\DeclarationId\NamedClassId;
use Typhoon\Reflection\ClassConstantReflection;
use Typhoon\Reflection\TyphoonReflector;
use Typhoon\Type\ShapeElement;
use Typhoon\Type\Type;
use Typhoon\Type\types;
use Typhoon\Type\Visitor\DefaultTypeVisitor;
use function Prototype\Serializer\Internal\Reflection\instanceOfDateInterval;
use function Prototype\Serializer\Internal\Reflection\instanceOfDateTime;
use function Typhoon\Type\stringify;

/**
 * @internal
 * @psalm-internal Prototype\Serializer
 * @template-extends DefaultTypeVisitor<PropertyMarshaller>
 */
final class NativeTypeToPropertyMarshallerConverter extends DefaultTypeVisitor
{
    private readonly NativeTypeToProtobufTypeConverter $nativeTypeToProtobufTypeConverter;

    public function __construct(
        private readonly TyphoonReflector $reflector,
    ) {
        $this->nativeTypeToProtobufTypeConverter = new NativeTypeToProtobufTypeConverter($this->reflector);
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
    public function self(Type $type, array $typeArguments, NamedClassId|AnonymousClassId|null $resolvedClassId): ObjectPropertyMarshaller
    {
        if (null !== ($class = $resolvedClassId?->name) && class_exists($class)) {
            return new ObjectPropertyMarshaller($class);
        }

        throw new TypeIsNotSupported(stringify($type));
    }

    /**
     * {@inheritdoc}
     */
    public function alias(Type $type, AliasId $aliasId, array $typeArguments): mixed
    {
        return $this->reflector->reflect($aliasId)->type()->accept($this);
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
    public function namedObject(Type $type, NamedClassId|AnonymousClassId $classId, array $typeArguments): PropertyMarshaller
    {
        try {
            return new ScalarPropertyMarshaller($type->accept($this->nativeTypeToProtobufTypeConverter));
        } catch (\Throwable) {
            if (null !== ($className = $classId->name)) {
                /** @psalm-suppress ArgumentTypeCoercion */
                return match (true) {
                    enum_exists($className) => new EnumPropertyMarshaller($className),
                    instanceOfDateTime($className) => new DateTimePropertyMarshaller($className),
                    instanceOfDateInterval($className) => new DateIntervalPropertyMarshaller(),
                    class_exists($className) => new ObjectPropertyMarshaller($className),
                    default => throw new TypeIsNotSupported($className),
                };
            }
        }

        throw new TypeIsNotSupported(stringify($type));
    }

    /**
     * {@inheritdoc}
     */
    public function iterable(Type $type, Type $keyType, Type $valueType): mixed
    {
        return match (true) {
            $keyType->accept(new IsString()) && $valueType->accept(new IsMixed()) => new StructPropertyMarshaller(),
            $keyType->accept(new IsMixed()) => new ArrayPropertyMarshaller($valueType->accept($this)),
            default => new HashTablePropertyMarshaller(
                new ScalarPropertyMarshaller($keyType->accept($this->nativeTypeToProtobufTypeConverter)),
                $valueType->accept($this),
            ),
        };
    }

    /**
     * {@inheritdoc}
     */
    public function union(Type $type, array $ofTypes): mixed
    {
        if ($type->accept(new IsConstantEnum($this->reflector))) {
            $intValue = new ToIntValue($this->reflector);

            return new ConstantEnumPropertyMarshaller(
                stringify($type),
                array_map(static fn (Type $type): int => $type->accept($intValue), $ofTypes),
            );
        }

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

        return $propertyMarshallers; // @phpstan-ignore-line
    }

    /**
     * {@inheritdoc}
     */
    public function classConstantMask(Type $type, Type $classType, string $namePrefix): mixed
    {
        $types = $this
            ->reflector
            ->reflect($classType->accept(new ClassResolver()))
            ->constants()
            ->filter(static fn (ClassConstantReflection $reflection): bool => str_starts_with($reflection->id->name, $namePrefix))
            ->map(static fn (ClassConstantReflection $reflection): Type => $reflection->type());

        return types::union(...$types->toList())->accept($this);
    }

    /**
     * {@inheritdoc}
     */
    protected function default(Type $type): ScalarPropertyMarshaller
    {
        return new ScalarPropertyMarshaller($type->accept($this->nativeTypeToProtobufTypeConverter));
    }
}
