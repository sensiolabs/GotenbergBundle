<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\Attributes\WithConfigurationNode;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\Enumeration\PdfFormat;
use Sensiolabs\GotenbergBundle\NodeBuilder\BooleanNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\NativeEnumNodeBuilder;

/**
 * @see https://gotenberg.dev/docs/routes#pdfa-chromium
 */
trait PdfFormatTrait
{
    abstract protected function getBodyBag(): BodyBag;

    /**
     * Convert the resulting PDF into the given PDF/A format.
     */
    #[WithConfigurationNode(new NativeEnumNodeBuilder('pdf_format', enumClass: PdfFormat::class))]
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
     * Enable PDF for Universal Access for optimal accessibility.
     */
    #[WithConfigurationNode(new BooleanNodeBuilder('pdf_universal_access'))]
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
