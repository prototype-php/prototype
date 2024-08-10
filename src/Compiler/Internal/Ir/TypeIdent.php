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
enum TypeIdent
{
    public static function fromType(Parser\Context\Type_Context $context): ProtoType
    {
        return match (true) {
            null !== $context->messageType() => self::message(toNonEmptyString($context->getText())),
            null !== $context->enumType() => self::enum(toNonEmptyString($context->getText())),
            default => self::scalar(Scalar::from(toNonEmptyString($context->getText()))),
        };
    }

    public static function fromElement(Parser\Context\MessageElementContext $context): ProtoType
    {
        $type = match (true) {
            null !== $context->field() => self::fromType($context->field()->type_()),
            null !== $context->mapField() => self::map(
                self::scalar(Scalar::from(toNonEmptyString($context->mapField()?->keyType()?->getText()))),
                self::fromType($context->mapField()?->type_()),
            ),
        };

        return $type;
    }

    public static function scalar(Scalar $scalar): Type\ScalarType
    {
        return new Type\ScalarType($scalar);
    }

    /**
     * @param non-empty-string $message
     */
    public static function message(string $message): Type\MessageType
    {
        return new Type\MessageType($message);
    }

    /**
     * @param non-empty-string $enum
     */
    public static function enum(string $enum): Type\EnumType
    {
        return new Type\EnumType($enum);
    }

    public static function repeated(ProtoType $type): Type\RepeatedType
    {
        return new Type\RepeatedType($type);
    }

    public static function map(ProtoType $keyType, ProtoType $valueType): Type\MapType
    {
        return new Type\MapType($keyType, $valueType);
    }

    /**
     * @param ProtoType[] $variants
     */
    public static function oneof(array $variants): Type\OneOfType
    {
        return new Type\OneOfType($variants);
    }
}
