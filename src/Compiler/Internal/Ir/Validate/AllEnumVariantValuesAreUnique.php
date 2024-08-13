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

namespace Prototype\Compiler\Internal\Ir\Validate;

use Prototype\Compiler\Internal\Ir\Enum;
use Prototype\Compiler\Internal\Ir\EnumCase;
use Prototype\Compiler\Internal\Ir\Hook\AfterEnumVisitedHook;

/**
 * @internal
 * @psalm-internal Prototype\Compiler
 */
final class AllEnumVariantValuesAreUnique implements AfterEnumVisitedHook
{
    public function afterEnumVisited(Enum $enum): void
    {
        /** @var array<int, EnumCase> $unique */
        $unique = [];

        foreach ($enum as $case) {
            if (isset($unique[$case->value])) {
                throw new ConstraintViolated(
                    \sprintf('Variants "%s" and "%s" of enum "%s" has the same value "%d".',
                        $case->name,
                        $unique[$case->value]->name,
                        $enum->name,
                        $case->value,
                    ),
                );
            }

            $unique[$case->value] = $case;
        }
    }
}
