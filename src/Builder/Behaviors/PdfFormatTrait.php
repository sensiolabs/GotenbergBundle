<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Enumeration\PdfFormat;

/**
 * @see https://gotenberg.dev/docs/routes#pdfa-chromium
 */
trait PdfFormatTrait
{
    abstract protected function getBodyBag(): BodyBag;

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
