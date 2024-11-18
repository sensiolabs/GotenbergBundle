<?php

namespace Sensiolabs\GotenbergBundle;

use Psr\Container\ContainerInterface;
use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\BuilderOld\Screenshot\HtmlScreenshotBuilder;
use Sensiolabs\GotenbergBundle\BuilderOld\Screenshot\MarkdownScreenshotBuilder;
use Sensiolabs\GotenbergBundle\BuilderOld\Screenshot\ScreenshotBuilderInterface;
use Sensiolabs\GotenbergBundle\BuilderOld\Screenshot\UrlScreenshotBuilder;

final class GotenbergScreenshot implements GotenbergScreenshotInterface
{
    public function __construct(
        private readonly ContainerInterface $container,
    ) {
    }

    public function get(string $builder): BuilderInterface
    {
        return $this->container->get($builder);
    }

    /**
     * @param 'html'|'url'|'markdown' $key
     *
     * @return (
     *   $key is 'html' ? HtmlScreenshotBuilder :
     *   $key is 'url' ? UrlScreenshotBuilder :
     *   $key is 'markdown' ? MarkdownScreenshotBuilder :
     *   ScreenshotBuilderInterface
     * )
     */
    private function getInternal(string $key): BuilderInterface
    {
        return $this->get(".sensiolabs_gotenberg.screenshot_builder.{$key}");
    }

    public function html(): BuilderInterface
    {
        return $this->getInternal('html');
    }

//    public function url(): ScreenshotBuilderInterface
//    {
//        return $this->getInternal('url');
//    }
//
//    public function markdown(): ScreenshotBuilderInterface
//    {
//        return $this->getInternal('markdown');
//    }
}
