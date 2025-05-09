<?php

namespace Sensiolabs\GotenbergBundle\Debug;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\HtmlScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\MarkdownScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\UrlScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Debug\Builder\TraceableBuilder;
use Sensiolabs\GotenbergBundle\GotenbergScreenshotInterface;

final class TraceableGotenbergScreenshot implements GotenbergScreenshotInterface
{
    /**
     * @var list<array{string, TraceableBuilder}>
     */
    private array $builders = [];

    public function __construct(
        private readonly GotenbergScreenshotInterface $inner,
    ) {
    }

    public function get(string $builder): BuilderInterface
    {
        $traceableBuilder = $this->inner->get($builder);

        if (!$traceableBuilder instanceof TraceableBuilder) {
            return $traceableBuilder;
        }

        $this->builders[] = [$builder, $traceableBuilder];

        return $traceableBuilder;
    }

    /**
     * @return HtmlScreenshotBuilder|TraceableBuilder
     */
    public function html(): BuilderInterface
    {
        /** @var HtmlScreenshotBuilder|TraceableBuilder $traceableBuilder */
        $traceableBuilder = $this->inner->html();

        if (!$traceableBuilder instanceof TraceableBuilder) {
            return $traceableBuilder;
        }

        $this->builders[] = ['html', $traceableBuilder];

        return $traceableBuilder;
    }

    /**
     * @return UrlScreenshotBuilder|TraceableBuilder
     */
    public function url(): BuilderInterface
    {
        /** @var UrlScreenshotBuilder|TraceableBuilder $traceableBuilder */
        $traceableBuilder = $this->inner->url();

        if (!$traceableBuilder instanceof TraceableBuilder) {
            return $traceableBuilder;
        }

        $this->builders[] = ['url', $traceableBuilder];

        return $traceableBuilder;
    }

    /**
     * @return MarkdownScreenshotBuilder|TraceableBuilder
     */
    public function markdown(): BuilderInterface
    {
        /** @var MarkdownScreenshotBuilder|TraceableBuilder $traceableBuilder */
        $traceableBuilder = $this->inner->markdown();

        if (!$traceableBuilder instanceof TraceableBuilder) {
            return $traceableBuilder;
        }

        $this->builders[] = ['markdown', $traceableBuilder];

        return $traceableBuilder;
    }

    /**
     * @return list<array{string, TraceableBuilder}>
     */
    public function getBuilders(): array
    {
        return $this->builders;
    }
}
