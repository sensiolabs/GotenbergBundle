<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\Attributes\ExposeSemantic;
use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\Enumeration\NodeType;
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
    #[ExposeSemantic('pdf_format', NodeType::Enum, ['default_null' => true, 'class' => PdfFormat::class, 'callback' => [PdfFormat::class, 'cases']])]
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
    #[ExposeSemantic('pdf_universal_access', NodeType::Boolean, ['default_null' => true])]
    public function pdfUniversalAccess(bool $bool = true): self
    {
        $this->getBodyBag()->set('pdfua', $bool);

        return $this;
    }

    #[NormalizeGotenbergPayload]
    private function normalizePdfFormat(): \Generator
    {
        yield 'pdfa' => NormalizerFactory::enum();
        yield 'pdfua' => NormalizerFactory::bool();
    }
}
