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

namespace Prototype\Compiler\Internal\Code;

use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\PromotedParameter;
use Prototype\Compiler\Internal\Ir;

/**
 * @internal
 * @psalm-internal Prototype\Compiler
 */
final class DefinitionGenerator
{
    public function __construct(
        private readonly PhpNamespace $namespace,
        private readonly Ir\TypeVisitor $typeVisitor,
    ) {}

    public function generateEnum(Ir\Enum $enum): void
    {
        $phpEnum = $this
            ->namespace
            ->addEnum(self::fixClassLikeName($enum->name))
            ->addComment('@api')
            ->setType('int')
        ;

        foreach ($enum as $enumCase) {
            $phpEnum->addCase(
                $enumCase->name,
                $enumCase->value,
            );
        }
    }

    public function generateClass(Ir\Message $message): void
    {
        $class = $this
            ->namespace
            ->addClass(self::fixClassLikeName($message->name))
            ->setFinal()
            ->addComment('@api')
        ;

        $method = $class
            ->addMethod('__construct')
        ;

        /** @var PromotedParameter[] $parameters */
        $parameters = [];

        foreach ($message as $field) {
            /** @var PhpType $type */
            $type = $field->type->accept($this->typeVisitor);

            if (null !== ($use = $type->use)) {
                $this->namespace->addUse($use);
            }

            $fieldName = self::snakeCaseToCamelCase($field->name);

            $parameters[] = (new PromotedParameter($fieldName))
                ->setReadOnly()
                ->setType($type->nativeType)
                ->setNullable($type->nullable)
                ->setDefaultValue($type->default)
            ;

            if ('' !== ($phpDoc = $type->toPhpDoc($fieldName))) {
                $method->addComment($phpDoc);
            }
        }

        $method->setParameters($parameters);
    }

    /**
     * @param non-empty-string $name
     * @return non-empty-string
     */
    private static function fixClassLikeName(string $name): string
    {
        /** @var non-empty-string */
        return str_replace('.', '', $name);
    }

    /**
     * @param non-empty-string $name
     * @return non-empty-string
     */
    private static function snakeCaseToCamelCase(string $name): string
    {
        /** @var non-empty-string */
        return lcfirst(
            str_replace(' ', '',
                ucwords(
                    str_replace('_', ' ', $name),
                ),
            ),
        );
    }
}
