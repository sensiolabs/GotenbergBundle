<?php

namespace Sensiolabs\GotenbergBundle\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\AbstractBuilder;
use Sensiolabs\GotenbergBundle\Builder\Attributes\WithBuilderConfiguration;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\AssetBaseDirFormatterAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\DownloadFromTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\EmbedTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\FilesTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\WebhookTrait;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;

/**
 * @methodDoc files Add PDF files which is the source of embedded file.
 * As assets files, by default the PDF files are fetch in the assets folder
 * of your application. For more information about path resolution go to
 * assets documentation.
 *
 * @see https://gotenberg.dev/docs/manipulate-pdfs/attachments
 *
 * @example files('document.pdf','document_2.pdf')
 */
#[WithBuilderConfiguration(type: 'pdf', name: 'embed')]
final class EmbedPdfBuilder extends AbstractBuilder
{
    use AssetBaseDirFormatterAwareTrait;
    use DownloadFromTrait;
    use EmbedTrait;
    use FilesTrait;
    use WebhookTrait;

    public const ENDPOINT = '/forms/pdfengines/embed';

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
        $this->introducedIn('8.25');

        if ($this->getBodyBag()->get('files') === null && $this->getBodyBag()->get('downloadFrom') === null) {
            throw new MissingRequiredFieldException('At least one PDF file is required.');
        }

        if ($this->getBodyBag()->get('embeds') === null) {
            throw new MissingRequiredFieldException('At least one embed file is required.');
        }
    }
}
