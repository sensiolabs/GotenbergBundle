<?php

namespace Sensiolabs\GotenbergBundle;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\HtmlScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\MarkdownScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\UrlScreenshotBuilder;

interface GotenbergScreenshotInterface
{
    /**
     * @template T of BuilderInterface
     *
     * @param string|class-string<T> $builder
     *
     * @return BuilderInterface
     */
    public function get(string $builder);

    public function html(): HtmlScreenshotBuilder;

    public function url(): UrlScreenshotBuilder;

    public function markdown(): MarkdownScreenshotBuilder;
}
