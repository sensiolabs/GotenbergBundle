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
     * @return ($key is 'url' ? UrlScreenshotBuilder :
     *     $key is 'markdown' ? MarkdownScreenshotBuilder :
     *     $key is 'html' ? HtmlScreenshotBuilder :
     *      ScreenshotBuilderInterface
     * )
     */
    private function getInternal(string $key): ScreenshotBuilderInterface
    {
        return $this->get(".sensiolabs_gotenberg.builder.{$key}");
    }

    public function html(): HtmlScreenshotBuilder
    {
        return $this->getInternal('html');
    }

    public function url(): UrlScreenshotBuilder
    {
        return $this->getInternal('url');
    }

    public function markdown(): MarkdownScreenshotBuilder
    {
        return $this->getInternal('markdown');
    }
}
