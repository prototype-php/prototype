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
use Prototype\Compiler\Internal\Code\WellKnown;
use Prototype\Compiler\Internal\Ir\ProtoType;

/**
 * @internal
 * @psalm-internal Prototype\Compiler
 * @template-extends DefaultTypeVisitor<PhpType>
 */
final class WellKnownTypeVisitor extends DefaultTypeVisitor
{
    /**
     * @var array<non-empty-string, PhpType>
     */
    private readonly array $types;

    public function __construct()
    {
        $this->types = [...WellKnown::typeToPhpType()];
    }

    /**
     * {@inheritdoc}
     */
    public function message(ProtoType $type, string $message): PhpType
    {
        return $this->types[$message] ?? throw new TypeNotFound($message);
    }
}
