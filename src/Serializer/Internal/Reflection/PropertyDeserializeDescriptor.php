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

use Prototype\Byte;
use Prototype\Serializer\Internal\Label\Labels;
use Prototype\Serializer\Internal\Wire\Tag;
use Prototype\Serializer\PrototypeException;

/**
 * @internal
 * @template-covariant T
 * @psalm-internal Prototype\Serializer
 */
final class PropertyDeserializeDescriptor
{
    /**
     * @param PropertyMarshaller<T> $marshaller
     */
    public function __construct(
        private readonly \ReflectionProperty $property,
        private readonly PropertyMarshaller $marshaller,
    ) {}

    /**
     * @return T
     * @throws PrototypeException
     */
    public function default(): mixed
    {
        /** @var T */
        return $this->marshaller->labels()[Labels::default];
    }

    /**
     * @return T
     * @throws \ReflectionException
     * @throws PrototypeException
     */
    public function readValue(Byte\Reader $reader, Deserializer $deserializer, Tag $tag): mixed
    {
        return $this->marshaller->deserializeValue($reader, $deserializer, $tag);
    }

    /**
     * @template TClass of object
     * @param TClass $object
     */
    public function setValue(object $object, mixed $value): void
    {
        $this->property->setValue($object, $value);
    }

    /**
     * @template TClass of object
     * @param TClass $object
     */
    public function setDefault(object $object, mixed $value): void
    {
        $this->setValue($object, $this->property->getType()?->allowsNull() ? null : $value);
    }

    /**
     * @template TClass of object
     * @param TClass $object
     */
    public function isInitialized(object $object): bool
    {
        return $this->property->isInitialized($object);
    }
}
