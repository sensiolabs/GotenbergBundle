<?php

namespace Sensiolabs\GotenbergBundle\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\AbstractBuilder;
use Sensiolabs\GotenbergBundle\Builder\Attributes\WithBuilderConfiguration;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\AssetBaseDirFormatterAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\DownloadFromTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\FilesTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\StampTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\WebhookTrait;
use Sensiolabs\GotenbergBundle\Enumeration\StampSource;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;

/**
 * Stamp PDF files.
 *
 * @methodDoc files Add PDF files to stamp.
 * As assets files, by default the PDF files are fetch in the assets folder
 * of your application. For more information about path resolution go to
 * assets documentation.
 *
 * @see https://gotenberg.dev/docs/manipulate-pdfs/stamp-pdfs
 *
 * @example files('document.pdf')
 */
#[WithBuilderConfiguration(type: 'pdf', name: 'stamp')]
final class StampPdfBuilder extends AbstractBuilder
{
    use AssetBaseDirFormatterAwareTrait;
    use DownloadFromTrait;
    use FilesTrait;
    use StampTrait;
    use WebhookTrait;

    public const ENDPOINT = '/forms/pdfengines/stamp';

    private const AVAILABLE_EXTENSIONS = ['pdf'];

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
        $this->introducedIn('8.28');

        if ($this->getBodyBag()->get('files') === null && $this->getBodyBag()->get('downloadFrom') === null) {
            throw new MissingRequiredFieldException('At least one PDF file is required.');
        }

        if ($this->getBodyBag()->get('stampSource') === null) {
            throw new MissingRequiredFieldException('Field "stampSource" must be provided.');
        }

        if ($this->getBodyBag()->get('stampExpression') === null) {
            throw new MissingRequiredFieldException('Field "stampExpression" must be provided.');
        }

        $source = $this->getBodyBag()->get('stampSource');
        if (\in_array($source, [StampSource::Image, StampSource::Pdf], true) && $this->getBodyBag()->get('stamp') === null) {
            throw new MissingRequiredFieldException('A stamp file is required when source is "image" or "pdf".');
        }
    }
}
