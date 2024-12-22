<?php

namespace Sensiolabs\GotenbergBundle\Twig;

use Sensiolabs\GotenbergBundle\Builder\Pdf\AbstractChromiumPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\AbstractChromiumScreenshotBuilder;

/**
 * @internal
 */
final class GotenbergAssetRuntime
{
    private AbstractChromiumPdfBuilder|AbstractChromiumScreenshotBuilder|null $builder = null;

    public function setBuilder(AbstractChromiumPdfBuilder|AbstractChromiumScreenshotBuilder|null $builder): void
    {
        $this->builder = $builder;
    }

    /**
     * This function is used to get the URL of an asset during the rendering
     * of a PDF or a screenshot with the Gotenberg client.
     *
     * It only works if the builder is an instance of AbstractChromiumPdfBuilder
     * or AbstractChromiumScreenshotBuilder.
     */
    public function getAssetUrl(string $path): string
    {
        if (null === $this->builder) {
            throw new \LogicException('You need to extend from AbstractChromiumPdfBuilder to use "gotenberg_asset" function.');
        }

        $this->builder->addAsset($path);

        return basename($path);
    }
}
