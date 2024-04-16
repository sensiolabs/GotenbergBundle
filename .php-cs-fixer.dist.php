<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude(__DIR__.'/var/')
;

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'array_indentation' => true,
        'blank_line_between_import_groups' => false,
        'compact_nullable_type_declaration' => true,
        'fully_qualified_strict_types' => true,
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
