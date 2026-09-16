<?php

namespace Sensiolabs\GotenbergBundle\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\AbstractBuilder;
use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\Attributes\WithBuilderConfiguration;
use Sensiolabs\GotenbergBundle\Builder\Attributes\WithConfigurationNode;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\AssetBaseDirFormatterAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\DownloadFromTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\FacturXTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\FilesTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\WebhookTrait;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\Enumeration\FacturXPdfFormat;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\NodeBuilder\BooleanNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\NativeEnumNodeBuilder;

/**
 * Embeds a Factur-X/ZUGFeRD e-invoice XML into an existing PDF, converting it to
 * PDF/A-3 (the only PDF/A family that allows embedded files).
 *
 * @see https://gotenberg.dev/docs/manipulate-pdfs/factur-x
 *
 * @methodDoc files The PDF file(s) to turn into a Factur-X/ZUGFeRD e-invoice.
 * As assets files, by default the PDF files are fetch in the assets folder
 * of your application. For more information about path resolution go to
 * assets documentation.
 *
 * @see https://gotenberg.dev/docs/manipulate-pdfs/factur-x
 *
 * @example files('invoice.pdf')
 */
#[WithBuilderConfiguration(type: 'pdf', name: 'factur_x')]
final class FacturXPdfBuilder extends AbstractBuilder
{
    use AssetBaseDirFormatterAwareTrait;
    use DownloadFromTrait;
    use FacturXTrait;
    use FilesTrait;
    use WebhookTrait;

    public const ENDPOINT = '/forms/pdfengines/factur-x';

    private const AVAILABLE_EXTENSIONS = [
        'pdf',
    ];

    /**
     * Convert the resulting PDF into the given PDF/A-3 variant, the only PDF/A family
     * that allows embedded files.
     *
     * @see https://gotenberg.dev/docs/manipulate-pdfs/factur-x
     *
     * @example pdfFormat(FacturXPdfFormat::Pdf3b)
     */
    #[WithConfigurationNode(new NativeEnumNodeBuilder('pdf_format', enumClass: FacturXPdfFormat::class))]
    public function pdfFormat(FacturXPdfFormat|null $format): self
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
     *
     * @see https://gotenberg.dev/docs/manipulate-pdfs/factur-x
     *
     * @example pdfUniversalAccess() // is same as `->pdfUniversalAccess(true)`
     */
    #[WithConfigurationNode(new BooleanNodeBuilder('pdf_universal_access'))]
    public function pdfUniversalAccess(bool $bool = true): self
    {
        $this->getBodyBag()->set('pdfua', $bool);

        return $this;
    }

    protected function getAllowedFilesExtensions(): array
    {
        return self::AVAILABLE_EXTENSIONS;
    }

    protected function getEndpoint(): string
    {
        return self::ENDPOINT;
    }

    protected function validatePayloadBody(): void
    {
        $this->introducedIn('8.34');

        if ($this->getBodyBag()->get('files') === null && $this->getBodyBag()->get('downloadFrom') === null) {
            throw new MissingRequiredFieldException('At least one PDF file is required.');
        }

        if ($this->getBodyBag()->get('facturxXml') === null) {
            throw new MissingRequiredFieldException('Field "facturxXml" must be provided.');
        }

        if ($this->getBodyBag()->get('facturxConformanceLevel') === null) {
            throw new MissingRequiredFieldException('Field "facturxConformanceLevel" must be provided.');
        }
    }

    #[NormalizeGotenbergPayload]
    private function normalizePdfFormat(): \Generator
    {
        yield 'pdfa' => NormalizerFactory::enum();
        yield 'pdfua' => NormalizerFactory::bool();
    }
}
