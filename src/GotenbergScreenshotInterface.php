<?php

namespace Sensiolabs\GotenbergBundle;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;

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

    public function html(): BuilderInterface;

    //    /**
    //     * @return UrlScreenshotBuilder
    //     */
    //    public function url(): BuilderInterface;
    //
    //    /**
    //     * @return MarkdownScreenshotBuilder
    //     */
    //    public function markdown(): ScreenshotBuilderInterface;
}
