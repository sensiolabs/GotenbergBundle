<?php

namespace Sensiolabs\GotenbergBundle\Debug;

use Sensiolabs\GotenbergBundle\Builder\Screenshot\HtmlScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\MarkdownScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\ScreenshotBuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\UrlScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Debug\Builder\TraceableScreenshotBuilder;
use Sensiolabs\GotenbergBundle\GotenbergScreenshotInterface;

final class TraceableGotenbergScreenshot implements GotenbergScreenshotInterface
{
    /**
     * @var list<array{string, TraceableScreenshotBuilder}>
     */
    private array $builders = [];

    public function __construct(
        private readonly GotenbergScreenshotInterface $inner,
    ) {
    }

    public function get(string $builder): ScreenshotBuilderInterface
    {
        $traceableBuilder = $this->inner->get($builder);

        if (!$traceableBuilder instanceof TraceableScreenshotBuilder) {
            return $traceableBuilder;
        }

        $this->builders[] = [$builder, $traceableBuilder];

        return $traceableBuilder;
    }

    /**
     * @return HtmlScreenshotBuilder|TraceableScreenshotBuilder
     */
    public function html(): ScreenshotBuilderInterface
    {
        /** @var HtmlScreenshotBuilder|TraceableScreenshotBuilder $traceableBuilder */
        $traceableBuilder = $this->inner->html();

        if (!$traceableBuilder instanceof TraceableScreenshotBuilder) {
            return $traceableBuilder;
        }

        $this->builders[] = ['html', $traceableBuilder];

        return $traceableBuilder;
    }

    /**
     * @return UrlScreenshotBuilder|TraceableScreenshotBuilder
     */
    public function url(): ScreenshotBuilderInterface
    {
        /** @var UrlScreenshotBuilder|TraceableScreenshotBuilder $traceableBuilder */
        $traceableBuilder = $this->inner->url();

        if (!$traceableBuilder instanceof TraceableScreenshotBuilder) {
            return $traceableBuilder;
        }

        $this->builders[] = ['url', $traceableBuilder];

        return $traceableBuilder;
    }

    /**
     * @return MarkdownScreenshotBuilder|TraceableScreenshotBuilder
     */
    public function markdown(): ScreenshotBuilderInterface
    {
        /** @var MarkdownScreenshotBuilder|TraceableScreenshotBuilder $traceableBuilder */
        $traceableBuilder = $this->inner->markdown();

        if (!$traceableBuilder instanceof TraceableScreenshotBuilder) {
            return $traceableBuilder;
        }

        $this->builders[] = ['markdown', $traceableBuilder];

        return $traceableBuilder;
    }

    /**
     * @return list<array{string, TraceableScreenshotBuilder}>
     */
    public function getBuilders(): array
    {
        return $this->builders;
    }
}
