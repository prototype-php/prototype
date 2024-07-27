<?php

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
