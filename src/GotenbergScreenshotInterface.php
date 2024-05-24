<?php

namespace Sensiolabs\GotenbergBundle;

use Sensiolabs\GotenbergBundle\Builder\Screenshot\HtmlScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\MarkdownScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\ScreenshotBuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\UrlScreenshotBuilder;

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
