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
     * @return ($builder is class-string ? T : BuilderInterface)
     */
    public function get(string $builder): BuilderInterface;

    /**
     * @return HtmlScreenshotBuilder
     */
    public function html(): BuilderInterface;

    /**
     * @return UrlScreenshotBuilder
     */
    public function url(): BuilderInterface;

    /**
     * @return MarkdownScreenshotBuilder
     */
    public function markdown(): BuilderInterface;
}
