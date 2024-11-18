<?php

namespace Sensiolabs\GotenbergBundle\Configurator;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\MergePdfBuilder;
use Sensiolabs\GotenbergBundle\Enumeration\PdfFormat;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;

/**
 * @extends AbstractBuilderConfigurator<MergePdfBuilder>
 */
class MergePdfBuilderConfigurator extends AbstractBuilderConfigurator
{
    protected function configure(BuilderInterface $builder, string $name, mixed $value): void
    {
        match ($name) {
            'pdf_format' => $builder->pdfFormat(PdfFormat::from($value)),
            'pdf_universal_access' => $builder->pdfUniversalAccess($value),
            'metadata' => $builder->metadata($value),
            default => throw new InvalidBuilderConfiguration(\sprintf('Invalid option "%s": no method does not exist in class "%s" to configured it.', $name, $builder::class)),
        };
    }
}
