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

namespace Kafkiansky\Prototype;

use Kafkiansky\Binary;
use Kafkiansky\Prototype\Internal\Type\ProtobufType;
use Kafkiansky\Prototype\Internal\Wire;
use Psr\SimpleCache\CacheInterface;
use Typhoon\Reflection\Cache\InMemoryCache;
use Typhoon\Reflection\TyphoonReflector;

/**
 * @api
 */
final class Serializer
{
    private readonly Wire\ProtobufMarshaller $marshaller;

    public function __construct(
        CacheInterface $cache = new InMemoryCache(),
    ) {
        $this->marshaller = new Wire\ProtobufMarshaller(
            TyphoonReflector::build(
                cache: $cache,
                customTypeResolver: ProtobufType::bool,
            ),
        );
    }

    /**
     * @template T of object
     * @param T $message
     * @throws Binary\BinaryException
     * @throws PrototypeException
     * @throws \ReflectionException
     */
    public function serialize(object $message, ?Binary\Buffer $buffer = null): Binary\Buffer
    {
        $buffer ??= Binary\Buffer::empty(Binary\Endianness::little());

        $this->marshaller->serialize($message, $buffer);

        return $buffer;
    }

    /**
     * @template T of object
     * @param class-string<T> $messageType
     * @return T
     * @throws \ReflectionException
     * @throws Binary\BinaryException
     * @throws PrototypeException
     */
    public function deserialize(Binary\Buffer $buffer, string $messageType): object
    {
        return $this->marshaller->deserialize($messageType, $buffer);
    }
}
