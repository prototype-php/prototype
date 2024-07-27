<?php

declare(strict_types=1);

namespace Kafkiansky\Prototype\Internal\Label;

use Kafkiansky\Prototype\Internal\Type\ProtobufType;
use Typhoon\TypedMap\Key;

/**
 * @internal
 * @psalm-internal Kafkiansky\Prototype
 * @psalm-immutable
 * @template-implements Key<ProtobufType>
 */
enum LabelSchemaType implements Key
{
    case key;
}
