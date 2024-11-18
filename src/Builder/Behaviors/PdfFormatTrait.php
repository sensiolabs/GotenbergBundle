<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Client\BodyBag;
use Sensiolabs\GotenbergBundle\Enumeration\PdfFormat;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @see https://gotenberg.dev/docs/routes#pdfa-chromium
 */
trait PdfFormatTrait
{
    abstract protected function getBodyBag(): BodyBag;

    protected function configure(OptionsResolver $bodyOptionsResolver, OptionsResolver $headersOptionsResolver): void
    {
        $bodyOptionsResolver
            ->define('pdfa')
            ->info('Convert the resulting PDF into the given PDF/A format.')
            ->allowedValues(
                ...array_map(fn (PdfFormat $p): string => $p->value, PdfFormat::cases()),
            )
        ;
        $bodyOptionsResolver
            ->define('pdfua')
            ->info('Enable PDF for Universal Access for optimal accessibility.')
            ->allowedTypes('bool')
        ;
    }

    /**
     * Convert the resulting PDF into the given PDF/A format.
     */
    public function pdfFormat(PdfFormat $format): self
    {
        $this->getBodyBag()->set('pdfa', $format->value);

        return $this;
    }

    /**
     * Enable PDF for Universal Access for optimal accessibility.
     */
    public function pdfUniversalAccess(bool $bool = true): self
    {
        $this->getBodyBag()->set('pdfua', $bool);

        return $this;
    }
}
