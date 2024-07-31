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

namespace Prototype\Serializer\Internal\Wire;

use Kafkiansky\Binary;
use Prototype\Serializer\Internal\Reflection;
use Prototype\Serializer\PrototypeException;
use Typhoon\Reflection\TyphoonReflector;

/**
 * @internal
 * @psalm-internal Prototype\Serializer
 */
final class ProtobufMarshaller implements
    Reflection\Serializer,
    Reflection\Deserializer
{
    private readonly Reflection\ProtobufReflector $protobufReflector;

    public function __construct(
        private readonly TyphoonReflector $classReflector,
    ) {
        $this->protobufReflector = new Reflection\ProtobufReflector();
    }

    /**
     * @template T of object
     * @param T $message
     * @throws \ReflectionException
     * @throws PrototypeException
     * @throws Binary\BinaryException
     */
    public function serialize(object $message, Binary\Buffer $buffer): void
    {
        $class = $this->classReflector->reflectClass($message::class);

        $properties = $this->protobufReflector->propertySerializers($class, $this->classReflector);

        /** @psalm-var \WeakMap<\ReflectionProperty, bool> $serialized */
        $serialized = new \WeakMap();

        foreach ($properties as $num => $propertySerializer) {
            if ($serialized[$propertySerializer->property] ?? false) {
                continue;
            }

            /** @psalm-suppress MixedAssignment It is ok here. */
            $propertyValue = $propertySerializer->value($message);

            if ($propertySerializer->isNotEmpty($propertyValue)) {
                $tag = new Tag($num, $propertySerializer->wireType());
                $propertySerializer->encode($propertyBuffer = $buffer->clone(), $this, $tag, $propertyValue);

                if (!$propertyBuffer->isEmpty()) {
                    $serialized[$propertySerializer->property] = true;

                    if ($propertySerializer->shouldSerializeTag()) {
                        $tag->encode($buffer);
                    }

                    $buffer->write($propertyBuffer->reset());
                }
            }
        }
    }

    /**
     * @template T of object
     * @param class-string<T> $messageType
     * @return T
     * @throws \ReflectionException
     * @throws PrototypeException
     * @throws Binary\BinaryException
     */
    public function deserialize(string $messageType, Binary\Buffer $buffer): object
    {
        $class = $this->classReflector->reflectClass($messageType);

        $object = $class
            ->toNativeReflection()
            ->newInstanceWithoutConstructor()
        ;

        $properties = $this->protobufReflector->propertyDeserializers($class, $this->classReflector);

        /** @psalm-var \WeakMap<Reflection\PropertyDeserializeDescriptor, ValueContext> $values */
        $values = new \WeakMap();

        while (!$buffer->isEmpty()) {
            $tag = Tag::decode($buffer);

            // Discard bytes for the unknown field.
            if (!isset($properties[$tag->num])) {
                discard($buffer, $tag);

                continue;
            }

            $propertyDeserializer = $properties[$tag->num];
            $values[$propertyDeserializer] ??= new ValueContext();
            /** @psalm-suppress MixedArgument */
            $values[$propertyDeserializer]->setValue($propertyDeserializer->readValue($buffer, $this, $tag)); // @phpstan-ignore-line
        }

        foreach ($values as $propertyDeserializer => $propertyValue) {
            $propertyDeserializer->setValue($object, $propertyValue->getValue());
        }

        // Additional cycle to set default values.
        // We could do it in one loop, but we have unions that refer to the same `\ReflectionProperty`,
        // and if the required unit variant serialized in the protobuf is not the first one in the schema,
        // we will set it to a default value (which is null), which we cannot change later for `readonly` properties.
        // We also use `setDefault` to set nullable fields to null instead of the default value,
        // so that we can distinguish between a real value and no value.
        foreach ($properties as $propertyDeserializer) {
            if (!$propertyDeserializer->isInitialized($object)) {
                $propertyDeserializer->setDefault($object, $propertyDeserializer->default());
            }
        }

        return $object;
    }
}
