<?php

namespace Sensiolabs\GotenbergBundle\PayloadResolver\Behaviors;

use Sensiolabs\GotenbergBundle\Enumeration\PdfFormat;
use Symfony\Component\OptionsResolver\OptionsResolver;

trait PdfFormatOptionsTrait
{
    abstract protected function getBodyOptionsResolver(): OptionsResolver;

    protected function configureOptions(): void
    {
        $this->getBodyOptionsResolver()
            ->define('pdfa')
            ->info('Convert the resulting PDF into the given PDF/A format.')
            ->allowedValues(...PdfFormat::cases())
        ;
        $this->getBodyOptionsResolver()
            ->define('pdfua')
            ->info('Enable PDF for Universal Access for optimal accessibility.')
            ->allowedTypes('bool')
        ;
    }
}
