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

namespace Prototype\Compiler\Internal\Code\Type;

use Prototype\Compiler\Exception\TypeNotFound;
use Prototype\Compiler\Internal\Code\PhpType;
use Prototype\Compiler\Internal\Ir\Enum;
use Prototype\Compiler\Internal\Ir\Message;
use Prototype\Compiler\Internal\Ir\Proto;
use Prototype\Compiler\Internal\Ir\ProtoType;
use Prototype\Compiler\Internal\Ir\Trace\TypeStorage;

/**
 * @internal
 * @psalm-internal Prototype\Compiler
 * @template-extends DefaultTypeVisitor<PhpType>
 */
final class ResolveLocalReferenceTypeVisitor extends DefaultTypeVisitor
{
    public function __construct(
        private readonly TypeStorage $types,
        private readonly Proto $proto,
    ) {}

    /**
     * {@inheritdoc}
     */
    public function message(ProtoType $type, string $message): PhpType
    {
        if (null !== ($reference = $this->types->resolveType($message))) {
            return PhpType::class($reference);
        }

        if (null !== ($definition = $this->proto->definitions[$message] ?? null)) {
            return match (true) {
                $definition instanceof Message => PhpType::class($definition->typeStorage()->typeName()),
                $definition instanceof Enum => PhpType::class($definition->name),
                default => throw new TypeNotFound($message),
            };
        }

        throw new TypeNotFound($message);
    }
}
