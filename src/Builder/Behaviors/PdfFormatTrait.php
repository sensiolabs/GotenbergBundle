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
            ->allowedValues(PdfFormat::cases())
        ;
        $bodyOptionsResolver
            ->define('pdfua')
            ->info('Enable PDF for Universal Access for optimal accessibility.')
            ->allowedTypes('bool')
        ;
    }

    /**
     * Enable PDF for Universal Access for optimal accessibility. (default false).
     */
    public function pdfFormat(PdfFormat|null $format): self
    {
        if (!$format) {
            $this->getBodyBag()->unset('pdfa');
        } else {
            $this->getBodyBag()->set('pdfa', $format);
        }

        return $this;
    }

    /**
     * Enable PDF for Universal Access for optimal accessibility. (default false).
     */
    public function pdfUniversalAccess(bool $bool = true): self
    {
        $this->getBodyBag()->set('pdfua', $bool);

        return $this;
    }
}
