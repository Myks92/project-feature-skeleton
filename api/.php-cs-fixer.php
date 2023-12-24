<?php

declare(strict_types=1);

return
    (new PhpCsFixer\Config())
        ->setCacheFile(__DIR__ . '/var/cache/.php_cs')
        ->setFinder(
            PhpCsFixer\Finder::create()
                ->in([
                    __DIR__ . '/bin',
                    __DIR__ . '/config',
                    __DIR__ . '/public',
                    __DIR__ . '/src',
                    __DIR__ . '/tests',
                ])
                ->append([
                    __FILE__,
                ])
        )
        ->setRules([
            '@Symfony' => true,
            '@PER' => true,
            '@PSR12:risky' => true,
            '@DoctrineAnnotation' => true,
            '@PHP83Migration' => true,
            '@PHPUnit100Migration:risky' => true,
            '@PhpCsFixer' => true,
            '@PhpCsFixer:risky' => true,

            'concat_space' => ['spacing' => 'one'],
            'cast_spaces' => ['space' => 'none'],
            'binary_operator_spaces' => false,

            'class_attributes_separation' => [
                'elements' => [
                    'const' => 'none',
                    'method' => 'one',
                    'property' => 'none',
                    'trait_import' => 'none',
                    'case' => 'none',
                ],
            ],

            'phpdoc_to_comment' => false,
            'phpdoc_separation' => false,
            'phpdoc_types_order' => ['null_adjustment' => 'always_last'],
            'phpdoc_align' => false,

            'operator_linebreak' => false,

            'global_namespace_import' => true,

            'blank_line_before_statement' => false,
            'multiline_whitespace_before_semicolons' => ['strategy' => 'no_multi_line'],

            'fopen_flags' => ['b_mode' => true],

            'php_unit_strict' => false,
            'php_unit_internal_class' => ['types' => ['final', 'abstract']],
            'php_unit_test_class_requires_covers' => false,
            'php_unit_test_case_static_method_calls' => ['call_type' => 'self'],

            'yoda_style' => false,

            'final_public_method_for_abstract_class' => true,
            'self_static_accessor' => true,

            'static_lambda' => true,

            'date_time_immutable' => true,
        ]);
