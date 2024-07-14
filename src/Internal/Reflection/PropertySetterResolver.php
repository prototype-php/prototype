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

namespace Kafkiansky\Prototype\Internal\Reflection;

use Kafkiansky\Prototype\Exception\TypeIsNotSupported;
use Kafkiansky\Prototype\Internal\Type\ProtobufType;
use Kafkiansky\Prototype\Internal\Type\ProtobufTypeResolver;
use Kafkiansky\Prototype\Internal\Type\StringType;
use Kafkiansky\Prototype\Internal\Type\ValueType;
use Kafkiansky\Prototype\Internal\Type\VaruintType;
use Typhoon\Type\ArrayElement;
use Typhoon\Type\DefaultTypeVisitor;
use Typhoon\Type\Type;

/**
 * @internal
 * @psalm-internal Kafkiansky\Prototype
 * @template-extends DefaultTypeVisitor<PropertySetter>
 */
final class PropertySetterResolver extends DefaultTypeVisitor
{
    private readonly ProtobufTypeResolver $protobufTypeResolver;

    /**
     * @param array{?ProtobufType, ?ProtobufType} $mapType
     * @param ?array<non-empty-string, PropertySetter<mixed>> $shapeType
     */
    public function __construct(
        private readonly ?ProtobufType $scalarType = null,
        private readonly ?ProtobufType $listType = null,
        private readonly array $mapType = [null, null],
        private readonly ?array $shapeType = null,
    ) {
        $this->protobufTypeResolver = new ProtobufTypeResolver();
    }

    /**
     * {@inheritdoc}
     */
    public function list(Type $self, Type $value, array $elements): ListProperty
    {
        return new ListProperty(
            null !== $this->listType ? new ScalarProperty($this->listType) : $value->accept($this),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function array(Type $self, Type $key, Type $value, array $elements): StructProperty|MapProperty|ArrayShapeProperty
    {
        if ($this->shapeType !== null) {
            return new ArrayShapeProperty($this->shapeType);
        }

        if (\count($elements) > 0 ) {
            return new ArrayShapeProperty(
                array_merge(
                    ...array_map(
                        function (int|string $name, ArrayElement $element): array {
                            /** @var array<non-empty-string, PropertySetter<mixed>> */
                            return [(string) $name => $element->type->accept($this)];
                        },
                        array_keys($elements),
                        $elements,
                    ),
                ),
            );
        }

        try {
            // Because google.protobuf.struct is a special encoded type, we cannot use the regular MapProperty.
            if ($key->accept($this->protobufTypeResolver) instanceof StringType && $value->accept($this->protobufTypeResolver) instanceof ValueType) {
                return new StructProperty();
            }
        } catch (TypeIsNotSupported) {}

        /** @var PropertySetter<array-key> $keySetter */
        $keySetter = null !== $this->mapType[0] ? new ScalarProperty($this->mapType[0]) : $key->accept($this);

        return new MapProperty(
            $keySetter,
            null !== $this->mapType[1] ? new ScalarProperty($this->mapType[1]) : $value->accept($this),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function namedObject(Type $self, string $class, array $arguments): PropertySetter
    {
        /** @psalm-suppress ArgumentTypeCoercion **/
        return match (true) {
            enum_exists($class) => new EnumProperty(new VaruintType(), $class),
            instanceOfDateTime($class) => new DateTimeProperty($class),
            isClassOf($class, \DateInterval::class) => new DateIntervalProperty(),
            class_exists($class) => new MessageProperty($class),
            default => $this->default($self),
        };
    }

    /**
     * {@inheritdoc}
     */
    public function null(Type $self): NullProperty
    {
        return new NullProperty();
    }

    /**
     * {@inheritdoc}
     */
    public function union(Type $self, array $types): array
    {
        $propertySetters = [];

        foreach ($types as $type) {
            $propertySetters[] = $type->accept($this);
        }

        return $propertySetters;
    }

    /**
     * {@inheritdoc}
     */
    protected function default(Type $self): PropertySetter
    {
        return new ScalarProperty(
            $this->scalarType ?: $self->accept($this->protobufTypeResolver),
        );
    }
}
