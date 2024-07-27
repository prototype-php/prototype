<?php

declare(strict_types=1);

namespace Prototype\Serializer\Internal\Label;

use Prototype\Serializer\Internal\Type\ProtobufType;
use Typhoon\TypedMap\Key;

/**
 * @internal
 * @psalm-internal Prototype\Serializer
 * @psalm-immutable
 * @template-implements Key<ProtobufType>
 */
enum LabelSchemaType implements Key
{
    case key;
}
