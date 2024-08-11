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

use Prototype\Compiler\Internal\Ir\Trace\TypeStorage;
use Prototype\Compiler\Internal\Parser;

/**
 * @internal
 * @psalm-internal Prototype\Compiler
 */
final class EnumVisitor extends Parser\Protobuf3BaseVisitor
{
    public function __construct(
        private readonly TypeStorage $types,
        private readonly string $parentMessageName = '',
    ) {}

    public function visitEnumDef(Parser\Context\EnumDefContext $context): Enum
    {
        $enum = new Enum(
            toNonEmptyString(
                \sprintf('%s%s',
                    '' !== $this->parentMessageName ? $this->parentMessageName.'.' : '',
                    $ident = $context->enumName()?->ident()?->IDENTIFIER()?->getText(), // @phpstan-ignore-line
                ),
            ),
            array_map(
                fn (Parser\Context\EnumElementContext $context): EnumCase => $context->accept($this),
                $context->enumBody()?->enumElement() ?: [],
            ),
        );

        $this->types->fork($ident);

        return $enum;
    }

    public function visitEnumElement(Parser\Context\EnumElementContext $context): EnumCase
    {
        $number = (int) ($context->enumField()?->intLit()?->getText() ?: 0);

        if (null !== $context->enumField()?->MINUS()) {
            $number = -$number;
        }

        return new EnumCase(
            toNonEmptyString($context->enumField()?->ident()?->IDENTIFIER()?->getText()),
            $number,
        );
    }
}
