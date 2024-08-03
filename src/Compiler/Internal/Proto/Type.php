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

namespace Prototype\Compiler\Internal\Proto;

/**
 * @internal
 * @psalm-internal Prototype\Compiler
 */
enum Type: string
{
    case int32    = 'int32';
    case int64    = 'int64';
    case uint32   = 'uint32';
    case uint64   = 'uint64';
    case sint32   = 'sint32';
    case sint64   = 'sint64';
    case fixed32  = 'fixed32';
    case fixed64  = 'fixed64';
    case sfixed32 = 'sfixed32';
    case sfixed64 = 'sfixed64';
    case string   = 'string';
    case float    = 'float';
    case double   = 'double';
    case bool     = 'bool';
    case bytes    = 'bytes';

    public function toPhpDoc(string $paramName): string
    {
        return match ($this) {
            self::string,
            self::bool,
            self::float => '',
            default     => \sprintf('@param %s $%s', $this->value, $paramName)
        };
    }

    public function toNative(): string
    {
        return match ($this) {
            self::string,
            self::bytes  => 'string',
            self::bool   => 'bool',
            self::float,
            self::double => 'float',
            default      => 'int',
        };
    }
}
