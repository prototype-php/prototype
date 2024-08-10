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
final class ProtoVisitor extends Parser\Protobuf3BaseVisitor
{
    public function visitProto(Parser\Context\ProtoContext $context): Proto // @phpstan-ignore-line
    {
        $packageName = toNonEmptyString(
            $context->packageStatement(0)?->fullIdent()?->getText() ?: '', // @phpstan-ignore-line
        );

        $definitions = [];

        foreach ($context->topLevelDef() ?: [] as $def) { // @phpstan-ignore-line
            if (null !== $def->messageDef()) {
                $messageDefinitions = $def->messageDef()->accept(new MessageVisitor($packageName));

                foreach ($messageDefinitions as $name => $messageDef) {
                    if (isset($definitions[$name])) {
                        throw new \LogicException(\sprintf('Message "%s" from package "%s" is duplicated.', $name, $packageName));
                    }

                    $definitions[$name] = $messageDef;
                }
            }

            if (null !== $def->enumDef()) {
                /** @var Enum $enum */
                $enum = $def->enumDef()->accept(new EnumVisitor());

                if (isset($definitions[$enum->name])) {
                    throw new \LogicException(\sprintf('Enum "%s" from package "%s" is duplicated.', $enum->name, $packageName));
                }

                $definitions[$enum->name] = $enum;
            }
        }

        $options = array_map(
            static fn (Parser\Context\OptionStatementContext $context): Option => $context->accept(new OptionVisitor()),
            $context->optionStatement() ?: [],
        );

        return new Proto(
            $packageName,
            $definitions,
            $options,
        );
    }
}
