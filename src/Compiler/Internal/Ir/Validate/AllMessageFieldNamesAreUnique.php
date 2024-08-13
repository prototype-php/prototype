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

use Prototype\Compiler\Internal\Ir\Field;
use Prototype\Compiler\Internal\Ir\Hook\AfterMessageVisitedHook;
use Prototype\Compiler\Internal\Ir\Message;
use Prototype\Compiler\Internal\Naming;

/**
 * @internal
 * @psalm-internal Prototype\Compiler
 */
final class AllMessageFieldNamesAreUnique implements AfterMessageVisitedHook
{
    public function afterMessageVisited(Message $message): void
    {
        /** @var array<non-empty-string, Field> $unique */
        $unique = [];

        foreach ($message as $field) {
            $fieldName = Naming\SnakeCase::toCamelCase($field->name);

            if (isset($unique[$fieldName])) {
                throw new ConstraintViolated(
                    \sprintf('Message "%s" has fields with the same name "%s".', $message->name, $fieldName),
                );
            }

            $unique[$fieldName] = $field;
        }
    }
}
