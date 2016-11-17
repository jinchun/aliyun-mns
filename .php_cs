<?php

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->exclude('vendor')
    ->in(__DIR__)
;

return Symfony\CS\Config\Config::create()
    ->setUsingCache(true)
    ->fixers([
        '-psr0',
        '-concat_without_spaces',
        '-empty_return',
        '-phpdoc_no_empty_return', // non-compliance in Laravel generated files
        '-phpdoc_no_package', // non-compliance in Laravel generated files
        '-phpdoc_params', // param alignment inconsistent with PhpStorm
        '-phpdoc_separation', // non-compliance in Laravel generated files
        '-phpdoc_short_description', // no need to add dot at the end of short description
        '-phpdoc_to_comment', // allow use of docblock comme nt in function body
        '-pre_increment',
        '-unalign_double_arrow',
        '-unalign_equals',
        'align_double_arrow',
        'align_equals',
        'concat_with_spaces',
        'ereg_to_preg',
        'header_comment',
        'multiline_spaces_before_semicolon',
        'newline_after_open_tag',
        'ordered_use',
        'php4_constructor',
        'phpdoc_order',
        'php_unit_construct',
        'php_unit_strict',
        'short_array_syntax',
        'short_echo_tag',
        'strict_param',
        'whitespacy_lines',
    ])
    ->finder($finder)
    ;
