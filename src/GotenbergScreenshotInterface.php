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
     */
    public function get(string $builder): ScreenshotBuilderInterface;

    public function html(): HtmlScreenshotBuilder;

    public function url(): UrlScreenshotBuilder;

    public function markdown(): MarkdownScreenshotBuilder;
}
