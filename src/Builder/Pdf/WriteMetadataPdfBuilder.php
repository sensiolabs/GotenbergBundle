<?php

namespace Sensiolabs\GotenbergBundle\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\AbstractBuilder;
use Sensiolabs\GotenbergBundle\Builder\Attributes\WithBuilderConfiguration;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\AssetBaseDirFormatterAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\DownloadFromTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\FilesTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\MetadataTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\WebhookTrait;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;

/**
 * Writes metadata into existing PDF files.
 *
 * @see https://gotenberg.dev/docs/8.x/manipulate-pdfs/write-metadata
 *
 * @methodDoc files Add PDF files to write the metadata into.
 * As assets files, by default the PDF files are fetched in the assets folder
 * of your application. For more information about path resolution go to
 * assets documentation.
 * If you provide multiple PDF files you will get a ZIP archive containing all the PDF.
 *
 * @see https://gotenberg.dev/docs/8.x/manipulate-pdfs/write-metadata
 *
 * @example files('document.pdf','document_2.pdf')
 */
#[WithBuilderConfiguration(type: 'pdf', name: 'write_metadata')]
final class WriteMetadataPdfBuilder extends AbstractBuilder
{
    use AssetBaseDirFormatterAwareTrait;
    use DownloadFromTrait;
    use FilesTrait;
    use MetadataTrait;
    use WebhookTrait;

    public const ENDPOINT = '/forms/pdfengines/metadata/write';

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
        if ($this->getBodyBag()->get('files') === null && $this->getBodyBag()->get('downloadFrom') === null) {
            throw new MissingRequiredFieldException('At least one PDF file is required.');
        }

        if ([] === $this->getBodyBag()->get('metadata', [])) {
            throw new MissingRequiredFieldException('Field "metadata" must be provided.');
        }
    }
}
