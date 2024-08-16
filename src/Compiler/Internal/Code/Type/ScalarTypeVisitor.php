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

use Prototype\Compiler\Internal\Code\PhpType;
use Prototype\Compiler\Internal\Ir\ProtoType;
use Prototype\Compiler\Internal\Ir\Scalar;

/**
 * @internal
 * @psalm-internal Prototype\Compiler
 * @template-extends DefaultTypeVisitor<PhpType>
 */
final class ScalarTypeVisitor extends DefaultTypeVisitor
{
    /**
     * {@inheritdoc}
     */
    public function scalar(ProtoType $type, Scalar $scalar): PhpType
    {
        return match ($scalar) {
            Scalar::bool   => PhpType::scalar('bool', default: false),
            Scalar::string => PhpType::scalar('string', default: ''),
            Scalar::bytes  => PhpType::scalar('string', 'bytes', ''),
            Scalar::double => PhpType::scalar('float', 'double', 0),
            Scalar::float  => PhpType::scalar('float', default: 0),
            default        => PhpType::scalar('int', $scalar->value, 0),
        };
    }
}
