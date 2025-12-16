<?php

namespace Sensiolabs\GotenbergBundle\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\AbstractBuilder;
use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\Attributes\WithBuilderConfiguration;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\AssetBaseDirFormatterAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\DownloadFromTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\FilesTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\FlattenTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\PdfFormatTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\WebhookTrait;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;

/**
 * You may have the possibility to convert several PDF document.
 *
 * @see https://gotenberg.dev/docs/routes#convert-into-pdfa--pdfua-route
 *
 * @methodDoc files If you provide multiple PDF files you will get ZIP folder containing all the converted PDF.
 *
 * @example files('document.pdf', __DIR__'/../../public/document_2.pdf')
 */
#[WithBuilderConfiguration(type: 'pdf', name: 'convert')]
final class ConvertPdfBuilder extends AbstractBuilder
{
    use AssetBaseDirFormatterAwareTrait;
    use DownloadFromTrait;
    use FilesTrait;
    use FlattenTrait;
    use PdfFormatTrait;
    use WebhookTrait;

    public const ENDPOINT = '/forms/pdfengines/convert';

    private const AVAILABLE_EXTENSIONS = [
        'pdf',
    ];

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
        if ($this->getBodyBag()->get('pdfa') === null && $this->getBodyBag()->get('pdfua') === null) {
            throw new MissingRequiredFieldException('At least "pdfa" or "pdfua" must be provided.');
        }

        if ($this->getBodyBag()->get('files') === null && $this->getBodyBag()->get('downloadFrom') === null) {
            throw new MissingRequiredFieldException('At least one PDF file is required.');
        }
    }

    #[NormalizeGotenbergPayload]
    private function normalizeFiles(): \Generator
    {
        yield 'files' => NormalizerFactory::asset();
    }
}
