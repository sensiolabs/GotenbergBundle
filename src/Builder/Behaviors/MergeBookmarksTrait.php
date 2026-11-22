<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\Attributes\WithConfigurationNode;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\LoggerAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\NodeBuilder\BooleanNodeBuilder;

trait MergeBookmarksTrait
{
    use LoggerAwareTrait;

    abstract protected function getBodyBag(): BodyBag;

    /**
     * Extracts existing bookmarks from input files and offsets their page numbers
     * based on their position in the merged document (default false).
     *
     * @see https://gotenberg.dev/docs/8.x/manipulate-pdfs/merge-pdfs#bookmarks-pdf-engines
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

    /**
     * Adds a bookmark per merged document, labeled by its Title metadata (falling back to the filename) and pointing to its first page, with the document's own bookmarks nested underneath (default false).
     *
     * @see https://gotenberg.dev/docs/8.x/manipulate-pdfs/merge-pdfs#bookmarks-pdf-engines
     *
     * @example titleBookmarks() // is same as `->titleBookmarks(true)`
     */
    #[WithConfigurationNode(new BooleanNodeBuilder('title_bookmarks'))]
    public function titleBookmarks(bool $bool = true): static
    {
        $this->logWarningIfVersionIs('<', '8.36', 'The option titleBookmarks is not available.');

        $this->getBodyBag()->set('titleBookmarks', $bool);

        return $this;
    }

    #[NormalizeGotenbergPayload]
    private function normalizeMergeBookmarks(): \Generator
    {
        yield 'autoIndexBookmarks' => NormalizerFactory::bool();
        yield 'titleBookmarks' => NormalizerFactory::bool();
    }
}
