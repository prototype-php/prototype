<?php

declare(strict_types=1);

namespace Kafkiansky\Prototype\Internal\Reflection;

use Kafkiansky\Binary;
use Kafkiansky\Prototype\Internal\Wire;
use Kafkiansky\Prototype\PrototypeException;
use Typhoon\TypedMap\TypedMap;

/**
 * @template T
 */
interface PropertyMarshaller
{
    /**
     * @param T $value
     * @throws Binary\BinaryException
     * @throws PrototypeException
     * @throws \ReflectionException
     */
    public function serializeValue(Binary\Buffer $buffer, Serializer $serializer, mixed $value, Wire\Tag $tag): void;

    /**
     * @return T
     * @throws Binary\BinaryException
     * @throws \ReflectionException
     * @throws PrototypeException
     */
    public function deserializeValue(Binary\Buffer $buffer, Deserializer $deserializer, Wire\Tag $tag): mixed;

    /**
     * @throws PrototypeException
     */
    public function labels(): TypedMap;
}
