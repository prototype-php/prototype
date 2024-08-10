<?php

declare(strict_types=1);

namespace Prototype\Compiler\Internal\Ir;

use Psl\Type;

/**
 * @internal
 * @psalm-internal Prototype\Compiler
 * @param ?non-empty-string $value
 * @psalm-return ($value is null ? null : non-empty-string)
 */
function trimString(?string $value): ?string
{
    if (null !== $value) {
        $value = toNonEmptyString(trim($value, '"\''));
    }

    return $value;
}

/**
 * @return non-empty-string
 */
function toNonEmptyString(?string $value): string
{
    return Type\non_empty_string()->coerce($value);
}

/**
 * @return positive-int
 */
function toPositiveInt(null|int|string $value): int
{
    return Type\positive_int()->coerce($value);
}
