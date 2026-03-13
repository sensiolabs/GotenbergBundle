<?php

namespace Sensiolabs\GotenbergBundle\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\AbstractBuilder;
use Sensiolabs\GotenbergBundle\Builder\Attributes\WithBuilderConfiguration;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\AssetBaseDirFormatterAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\DownloadFromTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\FilesTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\WebhookTrait;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;

/**
 * You may have the possibility to flatten several PDF pages.
 * It combines all its contents into a single layer, making it non-editable
 * and ensuring that the document's integrity is maintained.
 *
 * @see https://gotenberg.dev/docs/manipulate-pdfs/flatten-pdfs
 *
 * @methodDoc files If you provide multiple PDF files you will get ZIP folder containing all the converted PDF.
 *
 * @example files('document.pdf', __DIR__'/../../public/document_2.pdf')
 */
#[WithBuilderConfiguration(type: 'pdf', name: 'flatten')]
final class FlattenPdfBuilder extends AbstractBuilder
{
    use AssetBaseDirFormatterAwareTrait;
    use DownloadFromTrait;
    use FilesTrait;
    use WebhookTrait;

    public const ENDPOINT = '/forms/pdfengines/flatten';

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
        $this->introducedIn('8.16');

        if ($this->getBodyBag()->get('files') === null) {
            throw new MissingRequiredFieldException('At least one PDF file is required.');
        }
    }
}
