<?php

namespace Sensiolabs\GotenbergBundle\Twig;

use Sensiolabs\GotenbergBundle\Builder\Pdf\AbstractChromiumPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\AbstractChromiumScreenshotBuilder;

/**
 * @internal
 */
final class GotenbergRuntime
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
        $this->addAsset($path, 'gotenberg_asset');

        return basename($path);
    }

    /**
     * @deprecated use "gotenberg_font_style_tag" instead
     */
    public function getFont(string $path, string $name): string
    {
        $this->addAsset($path, 'gotenberg_font');

        $name = htmlspecialchars($name);
        $basename = htmlspecialchars(basename($path));

        return '@font-face {
                font-family: "'.$name.'";
                src: url("'.$basename.'");
            }'
        ;
    }

    public function getFontStyleTag(string $path, string $name): string
    {
        $this->addAsset($path, 'gotenberg_font_style_tag');

        $name = htmlspecialchars($name);
        $basename = htmlspecialchars(basename($path));

        return '<style>@font-face {font-family: "'.$name.'";src: url("'.$basename.'");}</style>';
    }

    private function addAsset(string $path, string $function): void
    {
        if (null === $this->builder) {
            throw new \LogicException(\sprintf('The %s function must be used in a Gotenberg context.', $function));
        }

        $this->builder->addAsset($path);
    }
}
