<?php

namespace Sensiolabs\GotenbergBundle;

use Psr\Container\ContainerInterface;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\HtmlScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\MarkdownScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\ScreenshotBuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\UrlScreenshotBuilder;

final readonly class GotenbergScreenshot implements GotenbergScreenshotInterface
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function get(string $builder): ScreenshotBuilderInterface
    {
        return $this->container->get($builder);
    }

    /**
     * @param 'html'|'url'|'markdown' $key
     *
     * @return ($key is 'html' ? HtmlScreenshotBuilder :
     *     $key is 'url' ? UrlScreenshotBuilder :
     *     $key is 'markdown' ? MarkdownScreenshotBuilder :
     *      ScreenshotBuilderInterface)
     * )
     */
    private function getInternal(string $key): ScreenshotBuilderInterface
    {
        return $this->get(".sensiolabs_gotenberg.screenshot_builder.{$key}");
    }

    public function html(): ScreenshotBuilderInterface
    {
        return $this->getInternal('html');
    }

    public function url(): ScreenshotBuilderInterface
    {
        return $this->getInternal('url');
    }

    public function markdown(): ScreenshotBuilderInterface
    {
        return $this->getInternal('markdown');
    }
}
