<?php

namespace Sensiolabs\GotenbergBundle\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\AbstractBuilder;
use Sensiolabs\GotenbergBundle\Builder\Attributes\WithBuilderConfiguration;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\AssetBaseDirFormatterAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\DownloadFromTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\FilesTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\WebhookTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\WriteBookmarksTrait;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;

/**
 * Writes bookmarks into existing PDF files, replacing their outline.
 *
 * @see https://gotenberg.dev/docs/8.x/manipulate-pdfs/write-bookmarks
 *
 * @methodDoc files Add PDF files to write the bookmarks into.
 * As assets files, by default the PDF files are fetched in the assets folder
 * of your application. For more information about path resolution go to
 * assets documentation.
 * If you provide multiple PDF files you will get a ZIP archive containing all the PDF.
 *
 * @see https://gotenberg.dev/docs/8.x/manipulate-pdfs/write-bookmarks
 *
 * @example files('document.pdf','document_2.pdf')
 *
 * @methodDoc bookmarks Bookmarks to write. A list applies to every file, a map of filename to bookmarks applies to each file.
 * The map has the same shape as the answer of the read bookmarks builder, so you can read, edit and write back.
 * The `children` property allows nesting bookmarks to create a hierarchical table of contents.
 *
 * @see https://gotenberg.dev/docs/8.x/manipulate-pdfs/write-bookmarks
 *
 * @example bookmarks([['title' => 'Introduction', 'page' => 1, 'children' => [['title' => 'Overview', 'page' => 1]]], ['title' => 'Appendix', 'page' => 5]])
 * @example bookmarks(['document.pdf' => [['title' => 'Introduction', 'page' => 1]]])
 */
#[WithBuilderConfiguration(type: 'pdf', name: 'write_bookmarks')]
final class WriteBookmarksPdfBuilder extends AbstractBuilder
{
    use AssetBaseDirFormatterAwareTrait;
    use DownloadFromTrait;
    use FilesTrait;
    use WebhookTrait;
    use WriteBookmarksTrait;

    public const ENDPOINT = '/forms/pdfengines/bookmarks/write';

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
        $this->introducedIn('8.28');

        if ($this->getBodyBag()->get('files') === null && $this->getBodyBag()->get('downloadFrom') === null) {
            throw new MissingRequiredFieldException('At least one PDF file is required.');
        }

        if ([] === $this->getBodyBag()->get('bookmarks', [])) {
            throw new MissingRequiredFieldException('Field "bookmarks" must be provided.');
        }
    }
}
