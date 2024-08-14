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

namespace Prototype\Compiler\Internal\Ir\Hook;

use Prototype\Compiler\Internal\Ir\Enum;
use Prototype\Compiler\Internal\Ir\Message;
use Prototype\Compiler\Internal\Ir\Proto;

/**
 * @internal
 * @psalm-internal Prototype\Compiler
 */
final class Hooks
{
    /** @var list<AfterMessageVisitedHook> */
    private array $afterMessageVisitedHooks = [];

    /** @var list<AfterEnumVisitedHook>  */
    private array $afterEnumVisitedHooks = [];

    /** @var list<AfterProtoResolvedHook> */
    private array $afterProtoResolvedHooks = [];

    /**
     * @param iterable<AfterMessageVisitedHook|AfterEnumVisitedHook|AfterProtoResolvedHook> $hooks
     */
    public function __construct(iterable $hooks = [])
    {
        foreach ($hooks as $hook) {
            $this->addHook($hook);
        }
    }

    public function afterMessageVisited(Message $message): void
    {
        foreach ($this->afterMessageVisitedHooks as $hook) {
            $hook->afterMessageVisited($message);
        }
    }

    public function afterEnumVisited(Enum $enum): void
    {
        foreach ($this->afterEnumVisitedHooks as $hook) {
            $hook->afterEnumVisited($enum);
        }
    }

    /**
     * @param iterable<non-empty-string, Proto> $files
     */
    public function afterProtoResolved(iterable $files): void
    {
        foreach ($this->afterProtoResolvedHooks as $hook) {
            $hook->afterProtoResolved($files);
        }
    }

    public function addHook(AfterMessageVisitedHook|AfterEnumVisitedHook|AfterProtoResolvedHook $hook): void
    {
        if ($hook instanceof AfterMessageVisitedHook) {
            $this->afterMessageVisitedHooks[] = $hook;
        }

        if ($hook instanceof AfterEnumVisitedHook) {
            $this->afterEnumVisitedHooks[] = $hook;
        }

        if ($hook instanceof AfterProtoResolvedHook) {
            $this->afterProtoResolvedHooks[] = $hook;
        }
    }
}
