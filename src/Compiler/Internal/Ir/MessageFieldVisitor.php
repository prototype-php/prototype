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

namespace Prototype\Compiler\Internal\Ir;

use Prototype\Compiler\Internal\Parser;

/**
 * @internal
 * @psalm-internal Prototype\Compiler
 */
final class MessageFieldVisitor extends Parser\Protobuf3BaseVisitor
{
    public function visitMessageElement(Parser\Context\MessageElementContext $context): Field // @phpstan-ignore-line
    {
        if (null !== $context->oneof()) {
            $fieldName = toNonEmptyString($context->oneof()?->oneofName()?->getText());

            /** @var list<array{num:positive-int, type:ProtoType}> $types */
            $types = [];

            foreach ($context->oneof()->oneofField() ?: [] as $oneOfField) {
                $types[] = [
                    'num' => toPositiveInt($oneOfField->fieldNumber()?->getText()),
                    'type' => TypeIdent::fromType($oneOfField->type_()),
                ];
            }

            uasort($types, static fn (array $l, array $r): int => match (true) {
                $l['num'] === $r['num'] => throw new \LogicException(
                    \sprintf('Fields of oneof "%s" has the same order.', $fieldName),
                ),
                default => $l['num'] <=> $r['num'],
            });

            return new Field(
                $fieldName,
                TypeIdent::oneof(array_map(static fn (array $field): ProtoType => $field['type'], $types)),
                $types[array_key_first($types)]['num'],
            );
        }

        $fieldName = toNonEmptyString(match (true) {
            null !== $context->field() => $context->field()?->fieldName()?->ident()?->IDENTIFIER()?->getText(),
            null !== $context->mapField() => $context->mapField()?->mapName()?->ident()?->IDENTIFIER()?->getText(),
            default => new \RuntimeException('Unreachable.'),
        });

        return new Field(
            $fieldName,
            TypeIdent::fromElement($context),
            toPositiveInt(match (true) {
                null !== $context->field() => $context->field()?->fieldNumber()?->getText(),
                null !== $context->mapField() => $context->mapField()?->fieldNumber()?->getText(),
                default => new \RuntimeException('Unreachable.'),
            }),
        );
    }
}
