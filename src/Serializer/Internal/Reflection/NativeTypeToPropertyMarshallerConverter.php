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
use Typhoon\DeclarationId\AliasId;
use Typhoon\DeclarationId\AnonymousClassId;
use Typhoon\DeclarationId\NamedClassId;
use Typhoon\Reflection\ClassConstantReflection;
use Typhoon\Reflection\TyphoonReflector;
use Typhoon\Type\ShapeElement;
use Typhoon\Type\Type;
use Typhoon\Type\types;
use Typhoon\Type\Visitor\DefaultTypeVisitor;
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
    public function union(Type $type, array $ofTypes): mixed
    {
        if ($type->accept(new IsConstantEnum())) {
            return new ConstantEnumPropertyMarshaller(
                array_map(static fn (Type $type): int => $type->accept(new ToTypeInt()), $ofTypes), // @phpstan-ignore-line
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
            ->reflect($classType->accept(new /** @extends DefaultTypeVisitor<NamedClassId> */ class extends DefaultTypeVisitor {
                /**
                 * {@inheritdoc}
                 */
                public function namedObject(Type $type, NamedClassId $classId, array $typeArguments): mixed
                {
                    return $classId;
                }

                /**
                 * {@inheritdoc}
                 */
                public function self(Type $type, array $typeArguments, NamedClassId|AnonymousClassId|null $resolvedClassId): mixed
                {
                    return $resolvedClassId ?? $this->default($type); // @phpstan-ignore-line
                }

                /**
                 * {@inheritdoc}
                 */
                public function parent(Type $type, array $typeArguments, NamedClassId|AnonymousClassId|null $resolvedClassId): mixed
                {
                    return $resolvedClassId ?? $this->default($type); // @phpstan-ignore-line
                }

                /**
                 * {@inheritdoc}
                 */
                public function static(Type $type, array $typeArguments, NamedClassId|AnonymousClassId|null $resolvedClassId): mixed
                {
                    return $resolvedClassId ?? $this->default($type); // @phpstan-ignore-line
                }

                protected function default(Type $type): mixed
                {
                    throw new TypeIsNotSupported(stringify($type));
                }
            }))
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
