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

use Prototype\Compiler\Internal\Parser\Context;
use Prototype\Compiler\Internal\Parser\Protobuf3BaseVisitor;

/**
 * @internal
 * @psalm-internal Prototype\Compiler
 */
final class ServiceVisitor extends Protobuf3BaseVisitor
{
    /**
     * @param non-empty-string $packageName
     */
    public function __construct(
        private readonly string $packageName,
    ) {}

    /**
     * {@inheritdoc}
     */
    public function visitServiceDef(Context\ServiceDefContext $context): Service
    {
        return new Service(
            $this->packageName,
            toNonEmptyString($context->serviceName()?->ident()?->getText()),
            array_map(
                fn (Context\ServiceElementContext $context): Rpc => $context->accept($this),
                array_filter(
                    $context->serviceElement() ?: [],
                    static fn (Context\ServiceElementContext $context): bool => null !== $context->rpc(),
                ),
            ),
        );
    }

    public function visitRpc(Context\RpcContext $context): Rpc
    {
        return new Rpc(
            toNonEmptyString($context->rpcName()?->ident()?->getText()),
            new RpcType(
                toNonEmptyString($context->messageTypeIn?->getText()),
                null !== $context->streamIn,
            ),
            new RpcType(
                toNonEmptyString($context->messageTypeOut?->getText()),
                null !== $context->streamOut,
            ),
        );
    }
}
