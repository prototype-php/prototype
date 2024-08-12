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
final class MessageVisitor extends Parser\Protobuf3BaseVisitor
{
    /**
     * @param non-empty-string $packageName
     */
    public function __construct(
        private readonly string $packageName,
        private readonly TypeStorage $types,
        private readonly string $parentMessageName = '',
    ) {}

    /**
     * @return array<non-empty-string, Definition[]>
     */
    public function visitMessageDef(Parser\Context\MessageDefContext $context): array // @phpstan-ignore-line
    {
        $message = new Message(
            toNonEmptyString(
                \sprintf('%s%s',
                    '' !== $this->parentMessageName ? $this->parentMessageName.'.' : '',
                    $messageName = $context->messageName()?->ident()?->IDENTIFIER()?->getText(), // @phpstan-ignore-line
                ),
            ),
            $types = $this->types->fork($messageName),
            array_values(
                array_map(
                    static fn (Parser\Context\MessageElementContext $context): Field => $context->accept(new MessageFieldVisitor()),
                    array_filter(
                        $context->messageBody()?->messageElement() ?: [],
                        static fn (Parser\Context\MessageElementContext $context): bool => null === $context->messageDef() && null === $context->enumDef(),
                    ),
                ),
            ),
        );

        $definitions = [$message->name => $message];

        foreach ($context->messageBody()?->messageElement() ?: [] as $element) { // @phpstan-ignore-line
            if (null !== $element->messageDef()) {
                $messageElements = $element->messageDef()->accept(new self($this->packageName, $types, $message->name));

                foreach ($messageElements as $messageName => $messageElement) {
                    if (isset($definitions[$messageName])) {
                        throw new \LogicException(\sprintf('Message "%s" from package "%s" is duplicated.', $messageName, $this->packageName));
                    }

                    $definitions[$messageName] = $messageElement;
                }
            }

            if (null !== $element->enumDef()) {
                /** @var Enum $enum */
                $enum = $element->enumDef()->accept(new EnumVisitor($types, $message->name));

                if (isset($definitions[$enum->name])) {
                    throw new \LogicException(\sprintf('Enum "%s" from package "%s" is duplicated.', $enum->name, $this->packageName));
                }

                $definitions[$enum->name] = $enum;
            }
        }

        return $definitions;
    }
}
