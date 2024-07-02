<?php

$finder = (new PhpCsFixer\Finder())
    ->in([
        __DIR__ . '/config',
        __DIR__ . '/docs',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
;

return (new PhpCsFixer\Config())
    ->setParallelConfig(PhpCsFixer\Runner\Parallel\ParallelConfigFactory::detect())
    ->setUsingCache(false)
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'blank_line_between_import_groups' => false,
        'native_function_invocation' => true,
        'no_superfluous_elseif' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'nullable_type_declaration_for_default_null_value' => false,
        'nullable_type_declaration' => [
            'syntax' => 'union',
        ],
        'return_assignment' => true,
        'strict_param' => true,
        'trailing_comma_in_multiline' => [
            'elements' => ['arguments', 'arrays', 'match', 'parameters'],
        ],
        'void_return' => true,
        'yoda_style' => [
            'always_move_variable' => true,
        ],
    ])
    ->setFinder($finder)
;
