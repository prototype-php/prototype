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

namespace Prototype\PHPStan;

use PHPStan\Analyser\NameScope;
use PHPStan\PhpDoc\TypeNodeResolverExtension;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\IntegerRangeType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;

/**
 * @api
 */
final class ProtobufTypeExtension implements TypeNodeResolverExtension
{
    public function resolve(TypeNode $typeNode, NameScope $nameScope): ?Type
    {
        $types = iterator_to_array(self::types());

        if ($typeNode instanceof IdentifierTypeNode && isset($types[$typeNode->__toString()])) {
            return $types[$typeNode->__toString()];
        }

        return null;
    }

    /**
     * @return \Traversable<string, Type>
     */
    private static function types(): \Traversable
    {
        foreach (['int32', 'uint32', 'fixed32'] as $type) {
            yield $type => IntegerRangeType::fromInterval(0, 4294967295);
        }

        foreach (['sint32', 'sfixed32'] as $type) {
            yield $type => IntegerRangeType::fromInterval(-2147483648, 2147483647);
        }

        foreach (['int64', 'uint64', 'fixed64'] as $type) {
            yield $type => IntegerRangeType::fromInterval(0, null);
        }

        foreach (['sint64', 'sfixed64'] as $type) {
            yield $type => new IntegerType();
        }

        yield 'bytes' => new StringType();
    }
}
