<?php

namespace Sensiolabs\GotenbergBundle;

use Sensiolabs\GotenbergBundle\BuilderOld\Screenshot\HtmlScreenshotBuilder;
use Sensiolabs\GotenbergBundle\BuilderOld\Screenshot\MarkdownScreenshotBuilder;
use Sensiolabs\GotenbergBundle\BuilderOld\Screenshot\ScreenshotBuilderInterface;
use Sensiolabs\GotenbergBundle\BuilderOld\Screenshot\UrlScreenshotBuilder;

interface GotenbergScreenshotInterface
{
    /**
     * @template T of ScreenshotBuilderInterface
     *
     * @param string|class-string<T> $builder
     *
     * @return ($builder is class-string ? T : ScreenshotBuilderInterface)
     */
    public function get(string $builder): ScreenshotBuilderInterface;

    /**
     * @return HtmlScreenshotBuilder
     */
    public function html(): ScreenshotBuilderInterface;

    /**
     * @return UrlScreenshotBuilder
     */
    public function url(): ScreenshotBuilderInterface;

    /**
     * @return MarkdownScreenshotBuilder
     */
    public function markdown(): ScreenshotBuilderInterface;
}
