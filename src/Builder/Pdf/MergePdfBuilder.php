<?php

namespace Sensiolabs\GotenbergBundle\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\AbstractBuilder;
use Sensiolabs\GotenbergBundle\Builder\Attributes\WithBuilderConfiguration;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\AssetBaseDirFormatterAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\DownloadFromTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\EmbedTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\EncryptTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\FilesTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\FlattenTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\MetadataTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\PdfFormatTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\WebhookTrait;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;

/**
 * Merge `n` pdf files into a single one.
 *
 * @see https://gotenberg.dev/docs/manipulate-pdfs/merge-pdfs
 *
 * @methodDoc files Add PDF files to merge.
 * As assets files, by default the PDF files are fetch in the assets folder
 * of your application. For more information about path resolution go to
 * assets documentation.
 *
 * @see https://gotenberg.dev/docs/manipulate-pdfs/merge-pdfs
 *
 * @example files('document.pdf','document_2.pdf')
 */
#[WithBuilderConfiguration(type: 'pdf', name: 'merge')]
final class MergePdfBuilder extends AbstractBuilder
{
    use AssetBaseDirFormatterAwareTrait;
    use DownloadFromTrait;
    use EmbedTrait;
    use EncryptTrait;
    use FilesTrait;
    use FlattenTrait;
    use MetadataTrait;
    use PdfFormatTrait;
    use WebhookTrait;

    public const ENDPOINT = '/forms/pdfengines/merge';

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
    }
}
