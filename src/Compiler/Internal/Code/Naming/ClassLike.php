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

namespace Prototype\Compiler\Internal\Code\Naming;

/**
 * @internal
 * @psalm-internal Prototype\Compiler
 */
enum ClassLike
{
    /** @var array<non-empty-string, 1>  */
    private const builtin = [
        'string' => 1, 'int' => 1, 'float' => 1, 'bool' => 1, 'array' => 1, 'object' => 1,
        'callable' => 1, 'iterable' => 1, 'void' => 1, 'null' => 1, 'mixed' => 1, 'false' => 1,
        'never' => 1, 'true' => 1, 'self' => 1, 'parent' => 1, 'static' => 1,
        'include' => 1, 'include_once' => 1, 'eval' => 1, 'require' => 1, 'require_once' => 1, 'or' => 1, 'xor' => 1,
        'and' => 1, 'instanceof' => 1, 'new' => 1, 'clone' => 1, 'exit' => 1, 'if' => 1, 'elseif' => 1, 'else' => 1,
        'endif' => 1, 'echo' => 1, 'do' => 1, 'while' => 1, 'endwhile' => 1, 'for' => 1, 'endfor' => 1, 'foreach' => 1,
        'endforeach' => 1, 'declare' => 1, 'enddeclare' => 1, 'as' => 1, 'try' => 1, 'catch' => 1, 'finally' => 1,
        'throw' => 1, 'use' => 1, 'insteadof' => 1, 'global' => 1, 'var' => 1, 'unset' => 1, 'isset' => 1, 'empty' => 1,
        'continue' => 1, 'goto' => 1, 'function' => 1, 'const' => 1, 'return' => 1, 'print' => 1, 'yield' => 1, 'list' => 1,
        'switch' => 1, 'endswitch' => 1, 'case' => 1, 'default' => 1, 'break' => 1,
        'extends' => 1, 'implements' => 1, 'namespace' => 1, 'trait' => 1, 'interface' => 1, 'class' => 1, '__CLASS__' => 1,
        '__TRAIT__' => 1, '__FUNCTION__' => 1, '__METHOD__' => 1, '__LINE__' => 1, '__FILE__' => 1, '__DIR__' => 1,
        '__NAMESPACE__' => 1, 'fn' => 1, 'match' => 1, 'enum' => 1, 'abstract' => 1, 'final' => 1,
        'private' => 1, 'protected' => 1, 'public' => 1, 'readonly' => 1,
    ];

    /**
     * @param non-empty-string $name
     * @return non-empty-string
     */
    public static function name(string $name): string
    {
        /** @var non-empty-string $name */
        $name = str_replace('.', '', $name);
        $name = SnakeCase::toPascalCase($name);

        if (isset(self::builtin[strtolower($name)])) {
            $name = \sprintf('%s_', $name);
        }

        return $name;
    }
}
