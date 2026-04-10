<?php

namespace Sensiolabs\GotenbergBundle\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\AbstractBuilder;
use Sensiolabs\GotenbergBundle\Builder\Attributes\WithBuilderConfiguration;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\AssetBaseDirFormatterAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\DownloadFromTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\FilesTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\WatermarkTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\WebhookTrait;
use Sensiolabs\GotenbergBundle\Enumeration\WatermarkSource;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;

/**
 * Watermark PDF files.
 *
 * @methodDoc files Add PDF files to watermark.
 * As assets files, by default the PDF files are fetch in the assets folder
 * of your application. For more information about path resolution go to
 * assets documentation.
 *
 * @see https://gotenberg.dev/docs/manipulate-pdfs/watermark-pdfs
 *
 * @example files('document.pdf')
 */
#[WithBuilderConfiguration(type: 'pdf', name: 'watermark')]
final class WatermarkPdfBuilder extends AbstractBuilder
{
    use AssetBaseDirFormatterAwareTrait;
    use DownloadFromTrait;
    use FilesTrait;
    use WatermarkTrait;
    use WebhookTrait;

    public const ENDPOINT = '/forms/pdfengines/watermark';

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

        if ($this->getBodyBag()->get('watermarkSource') === null) {
            throw new MissingRequiredFieldException('Field "watermarkSource" must be provided.');
        }

        if ($this->getBodyBag()->get('watermarkExpression') === null) {
            throw new MissingRequiredFieldException('Field "watermarkExpression" must be provided.');
        }

        $source = $this->getBodyBag()->get('watermarkSource');
        if (\in_array($source, [WatermarkSource::Image, WatermarkSource::Pdf], true) && $this->getBodyBag()->get('watermark') === null) {
            throw new MissingRequiredFieldException('A watermark file is required when source is "image" or "pdf".');
        }
    }
}
