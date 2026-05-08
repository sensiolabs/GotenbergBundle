<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\Attributes\WithConfigurationNode;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\LoggerAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\NodeBuilder\BooleanNodeBuilder;

trait BookmarksTrait
{
    use LoggerAwareTrait;

    abstract protected function getBodyBag(): BodyBag;

    /**
     * Bookmarks to write (JSON). A list applies to the final merged PDF.
     * A map of filename→bookmarks shifts page indexes per file before merging.
     *
     * You can also provide custom bookmarks with the bookmarks form field. When provided as a list, it is applied
     * directly to the final merged PDF. When provided as a map of filename to bookmarks, page indexes are shifted per
     * file before merging.
     *
     * @param list<array{title: string, page: int, children?: list<mixed>}>|array<string, list<array{title: string, page: int, children?: list<mixed>}>> $bookmarks
     *
     * @see https://gotenberg.dev/docs/manipulate-pdfs/merge-pdfs#bookmarks-pdf-engines
     *
     * @example bookmarks([['title' => 'Introduction', 'page' => 1, 'children' => []], ['title' => 'Appendix', 'page' => 5, 'children' => []]])
     * @example bookmarks(['1_pdf.pdf' => [['title' => 'Introduction', 'page' => 1, 'children' => []]], '2_pdf.pdf' => [['title' => 'Appendix', 'page' => 1, 'children' => []]]])
     */
    public function bookmarks(array $bookmarks): static
    {
        $this->logWarningIfVersionIs('<', '8.28', 'The option bookmarks is not available.');

        $this->getBodyBag()->set('bookmarks', $bookmarks);

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
