<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude('vendor')
    ->exclude('templates')
    ->exclude('node_modules')
    ->in(__DIR__);

$config = new PhpCsFixer\Config();
$config->setRules([
        // Presets
        '@PSR12' => true,
        '@PHP83Migration' => true,
        '@PhpCsFixer' => true,

        // Disable rules
        'yoda_style' => false,
        'php_unit_internal_class' => false,
        'php_unit_test_class_requires_covers' => false,
        'multiline_whitespace_before_semicolons' => false,

        // Compact namespace configuration
        'blank_lines_before_namespace' => false,
        'single_blank_line_before_namespace' => false,
        'blank_line_after_opening_tag' => false,
        'linebreak_after_opening_tag' => false,

        // Options tweaks
        'concat_space' => ['spacing' => 'one'],
]);
$config->setFinder($finder);

return $config;
