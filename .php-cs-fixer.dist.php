<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create();
$finder->in(__DIR__ . \DIRECTORY_SEPARATOR . 'src');

return (new Config())
    ->setRiskyAllowed(true)
    ->setFinder($finder)
    ->setUsingCache(false)
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'blank_line_before_statement' => [
            'statements' => [
                'break',
                'case',
                'continue',
                'declare',
                'default',
                'do',
                'exit',
                'for',
                'foreach',
                'goto',
                'if',
                'include',
                'include_once',
                'require',
                'require_once',
                'return',
                'switch',
                'throw',
                'try',
                'while',
                'yield',
                'yield_from',
            ],
        ],
        'final_internal_class' => ['annotation_exclude' => ['@not-fix']],
        'phpdoc_add_missing_param_annotation' => true,
        'phpdoc_order' => true,
        'phpdoc_types_order' => true,
        'date_time_immutable' => true,
        'global_namespace_import' => ['import_classes' => true, 'import_constants' => true, 'import_functions' => true],
        'phpdoc_to_comment' => false,
        'blank_line_between_import_groups' => false,
        // strict
        'declare_strict_types' => true,
        'strict_comparison' => true,
        'strict_param' => true,
        'no_useless_else' => true,
        'concat_space' => ['spacing' => 'one'],
        'static_lambda' => true,
        'control_structure_continuation_position' => true,
        'date_time_create_from_format_call' => true,
        'nullable_type_declaration_for_default_null_value' => true,
    ]);
