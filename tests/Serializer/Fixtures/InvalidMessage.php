<?php

declare(strict_types=1);

namespace Prototype\Tests\Serializer\Fixtures;

final class InvalidMessage
{
    public function __construct(
        public readonly InvalidEnum $enum,
    ) {}
}
