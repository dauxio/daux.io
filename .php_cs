<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude('vendor')
    ->exclude('templates')
    ->in(__DIR__)
;

return PhpCsFixer\Config::create()
    ->setRules([
        '@PSR2' => true,
        '@PHP70Migration' => true,
        '@PHP71Migration' => true,
        '@PhpCsFixer' => true,
        'explicit_string_variable' => false,
        'single_blank_line_before_namespace' => false,
        'no_short_echo_tag' => false,
        'blank_line_after_opening_tag' => false,
        'yoda_style' => false,
        'concat_space' => ['spacing' => 'one'],
        'php_unit_internal_class' => false,
        'php_unit_test_class_requires_covers' => false,
        'phpdoc_align' => false,
        'multiline_whitespace_before_semicolons' => false,
        'ordered_class_elements' => ['use_trait', 'constant_public', 'constant_protected', 'constant_private', 'property_public', 'property_protected', 'property_private', 'construct', 'method']
    ])
    ->setFinder($finder)
;
