<?php

namespace Sensiolabs\GotenbergBundle\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\AbstractBuilder;
use Sensiolabs\GotenbergBundle\Builder\Attributes\WithBuilderConfiguration;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\AssetBaseDirFormatterAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\DownloadFromTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\FilesTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\ImageQualityTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\WebhookTrait;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;

/**
 * You may have the possibility to reduce the size of PDF files by re-encoding their images.
 *
 * @see https://gotenberg.dev/docs/manipulate-pdfs/optimize-pdfs
 *
 * @methodDoc files Add PDF files to optimize.
 * As assets files, by default the PDF files are fetched in the assets folder
 * of your application. For more information about path resolution go to
 * assets documentation.
 * If you provide multiple PDF files you will get a ZIP archive containing all the optimized PDF.
 *
 * @see https://gotenberg.dev/docs/manipulate-pdfs/optimize-pdfs
 *
 * @example files('document.pdf','document_2.pdf')
 *
 * @methodDoc imageQuality The JPEG quality applied to each re-encoded image, between 1 and 100. (default 80).
 *
 * @see https://gotenberg.dev/docs/manipulate-pdfs/optimize-pdfs
 *
 * @example imageQuality(60)
 */
#[WithBuilderConfiguration(type: 'pdf', name: 'optimize')]
final class OptimizePdfBuilder extends AbstractBuilder
{
    use AssetBaseDirFormatterAwareTrait;
    use DownloadFromTrait;
    use FilesTrait;
    use ImageQualityTrait;
    use WebhookTrait;

    public const ENDPOINT = '/forms/pdfengines/optimize';

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
        $this->introducedIn('8.36');

        if ($this->getBodyBag()->get('files') === null && $this->getBodyBag()->get('downloadFrom') === null) {
            throw new MissingRequiredFieldException('At least one PDF file is required.');
        }
    }
}
