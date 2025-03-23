<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        '@PSR12'                  => true,
        '@DoctrineAnnotation'     => true,
        '@PhpCsFixer'             => true,
        'global_namespace_import' => [
            'import_classes'   => true,
            'import_functions' => true,
            'import_constants' => true
        ],
        'concat_space'            => ['spacing' => 'one'],
        'new_with_braces'         => true,
        'phpdoc_to_comment'       => false,
        'binary_operator_spaces'  => [
            'operators' => [
                '='  => 'align',
                '=>' => 'align',
            ],
        ]
    ])
    ->setFinder($finder)
;
