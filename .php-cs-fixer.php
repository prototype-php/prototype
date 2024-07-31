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

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$config = (new Config())
    ->setFinder(
        Finder::create()
            ->in(__DIR__ . '/src')
            ->append([__FILE__])
            ->append(
                Finder::create()
                    ->in(__DIR__ . '/tests'),
            ),
    )
    ->setCacheFile(__DIR__.'/var/.php-cs-fixer.cache')
    ->setRiskyAllowed(true)
    ->setRules([
        'single_line_comment_style' => false,
        'phpdoc_to_comment' => false,
        'no_superfluous_phpdoc_tags' => false,
        'return_assignment' => false,
        '@PhpCsFixer:risky' => true,
        'strict_param' => false,
        'php_unit_strict' => false,
        'php_unit_construct' => false,
        '@PSR12:risky' => true,
        'blank_line_before_statement' => [
            'statements' => [
                'continue',
                'do',
                'exit',
                'goto',
                'if',
                'return',
                'switch',
                'throw',
                'try',
            ],
        ],
        'global_namespace_import' => ['import_classes' => false, 'import_constants' => false, 'import_functions' => false],
        'php_unit_internal_class' => false,
        'php_unit_test_case_static_method_calls' => ['call_type' => 'self'],
        'trailing_comma_in_multiline' => [
            'after_heredoc' => true,
            'elements' => ['arrays', 'arguments', 'parameters'],
        ],
        'array_syntax' => ['syntax' => 'short'],
        'native_constant_invocation' => true,
        'combine_consecutive_issets' => true,
        'strict_comparison' => false,
        'no_unset_on_property' => false,
        'no_extra_blank_lines' => false,
        'constant_case' => false,
    ])
;

return $config;
