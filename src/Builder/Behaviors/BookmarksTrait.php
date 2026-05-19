<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\Attributes\WithConfigurationNode;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\LoggerAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\Builder\Util\ValidatorFactory;
use Sensiolabs\GotenbergBundle\NodeBuilder\BooleanNodeBuilder;

/**
 * @phpstan-type Bookmark array{title: string, page: int, children?: list<array{title: string, page: int, children?: list<mixed>}>}
 */
trait BookmarksTrait
{
    use LoggerAwareTrait;

    abstract protected function getBodyBag(): BodyBag;

    /**
     * Bookmarks to write. When provided as a list, it is applied directly to the final merged PDF.
     * When provided as a map of filename to bookmarks, page indexes are shifted per file before merging.
     * The `children` property allows nesting bookmarks to create a hierarchical table of contents.
     *
     * @param list<Bookmark>|array<string, list<Bookmark>> $bookmarks
     *
     * @see https://gotenberg.dev/docs/manipulate-pdfs/merge-pdfs#bookmarks-pdf-engines
     *
     * @example bookmarks([['title' => 'Introduction', 'page' => 1, 'children' => [['title' => 'Overview', 'page' => 1]]], ['title' => 'Appendix', 'page' => 5]])
     * @example bookmarks(['1_pdf.pdf' => [['title' => 'Introduction', 'page' => 1]], '2_pdf.pdf' => [['title' => 'Appendix', 'page' => 1]]])
     */
    public function bookmarks(array $bookmarks): static
    {
        $this->logWarningIfVersionIs('<', '8.28', 'The option bookmarks is not available.');

        $this->getBodyBag()->set('bookmarks', $bookmarks);

        return $this;
    }

    /**
     * Adds a single bookmark entry to the existing list.
     * The `children` property allows nesting bookmarks to create a hierarchical table of contents.
     *
     * @param list<Bookmark> $children
     *
     * @see https://gotenberg.dev/docs/manipulate-pdfs/merge-pdfs#bookmarks-pdf-engines
     *
     * @example addBookmark('Introduction', 1)
     * @example addBookmark('Chapter 1', 1, [['title' => 'Overview', 'page' => 1]])
     */
    public function addBookmark(string $title, int $page, array $children = []): static
    {
        ValidatorFactory::page($page);

        $this->logWarningIfVersionIs('<', '8.28', 'The option bookmarks is not available.');

        /** @var list<Bookmark> $current */
        $current = $this->getBodyBag()->get('bookmarks', []);

        $bookmark = ['title' => $title, 'page' => $page];
        if ([] !== $children) {
            $bookmark['children'] = $children;
        }

        $this->getBodyBag()->set('bookmarks', [...$current, $bookmark]);

        return $this;
    }

    /**
     * Extracts existing bookmarks from input files and offsets their page numbers
     * based on their position in the merged document (default false).
     *
     * @see https://gotenberg.dev/docs/manipulate-pdfs/merge-pdfs#bookmarks-pdf-engines
     *
     * @example autoIndexBookmarks() // is same as `->autoIndexBookmarks(true)`
     */
    #[WithConfigurationNode(new BooleanNodeBuilder('auto_index_bookmarks'))]
    public function autoIndexBookmarks(bool $bool = true): static
    {
        $this->logWarningIfVersionIs('<', '8.28', 'The option autoIndexBookmarks is not available.');

        $this->getBodyBag()->set('autoIndexBookmarks', $bool);

        return $this;
    }

    #[NormalizeGotenbergPayload]
    private function normalizeBookmarks(): \Generator
    {
        yield 'bookmarks' => NormalizerFactory::json();
        yield 'autoIndexBookmarks' => NormalizerFactory::bool();
    }
}
