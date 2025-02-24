<?php

namespace Sensiolabs\GotenbergBundle\Debug;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Pdf\ConvertPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\FlattenPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\HtmlPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\LibreOfficePdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\MarkdownPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\MergePdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\SplitPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\UrlPdfBuilder;
use Sensiolabs\GotenbergBundle\Debug\Builder\TraceableBuilder;
use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

final class TraceableGotenbergPdf implements GotenbergPdfInterface
{
    /**
     * @var list<array{string, TraceableBuilder}>
     */
    private array $builders = [];

    public function __construct(
        private readonly GotenbergPdfInterface $inner,
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
     * @return HtmlPdfBuilder|TraceableBuilder
     */
    public function html(): BuilderInterface
    {
        /** @var HtmlPdfBuilder|TraceableBuilder $traceableBuilder */
        $traceableBuilder = $this->inner->html();

        if (!$traceableBuilder instanceof TraceableBuilder) {
            return $traceableBuilder;
        }

        $this->builders[] = ['html', $traceableBuilder];

        return $traceableBuilder;
    }

    /**
     * @return UrlPdfBuilder|TraceableBuilder
     */
    public function url(): BuilderInterface
    {
        /** @var UrlPdfBuilder|TraceableBuilder $traceableBuilder */
        $traceableBuilder = $this->inner->url();

        if (!$traceableBuilder instanceof TraceableBuilder) {
            return $traceableBuilder;
        }

        $this->builders[] = ['url', $traceableBuilder];

        return $traceableBuilder;
    }

    /**
     * @return MarkdownPdfBuilder|TraceableBuilder
     */
    public function markdown(): BuilderInterface
    {
        /** @var MarkdownPdfBuilder|TraceableBuilder $traceableBuilder */
        $traceableBuilder = $this->inner->markdown();

        if (!$traceableBuilder instanceof TraceableBuilder) {
            return $traceableBuilder;
        }

        $this->builders[] = ['markdown', $traceableBuilder];

        return $traceableBuilder;
    }

    /**
     * @return LibreOfficePdfBuilder|TraceableBuilder
     */
    public function office(): BuilderInterface
    {
        /** @var LibreOfficePdfBuilder|TraceableBuilder $traceableBuilder */
        $traceableBuilder = $this->inner->office();

        if (!$traceableBuilder instanceof TraceableBuilder) {
            return $traceableBuilder;
        }

        $this->builders[] = ['office', $traceableBuilder];

        return $traceableBuilder;
    }

    /**
     * @return MergePdfBuilder|TraceableBuilder
     */
    public function merge(): BuilderInterface
    {
        /** @var MergePdfBuilder|TraceableBuilder $traceableBuilder */
        $traceableBuilder = $this->inner->merge();

        if (!$traceableBuilder instanceof TraceableBuilder) {
            return $traceableBuilder;
        }

        $this->builders[] = ['merge', $traceableBuilder];

        return $traceableBuilder;
    }

    /**
     * @return ConvertPdfBuilder|TraceableBuilder
     */
    public function convert(): BuilderInterface
    {
        /** @var ConvertPdfBuilder|TraceableBuilder $traceableBuilder */
        $traceableBuilder = $this->inner->convert();

        if (!$traceableBuilder instanceof TraceableBuilder) {
            return $traceableBuilder;
        }

        $this->builders[] = ['convert', $traceableBuilder];

        return $traceableBuilder;
    }

    /**
     * @return SplitPdfBuilder|TraceableBuilder
     */
    public function split(): BuilderInterface
    {
        /** @var SplitPdfBuilder|TraceableBuilder $traceableBuilder */
        $traceableBuilder = $this->inner->split();

        if (!$traceableBuilder instanceof TraceableBuilder) {
            return $traceableBuilder;
        }

        $this->builders[] = ['split', $traceableBuilder];

        return $traceableBuilder;
    }

    /**
     * @return FlattenPdfBuilder|TraceableBuilder
     */
    public function flatten(): BuilderInterface
    {
        /** @var FlattenPdfBuilder|TraceableBuilder $traceableBuilder */
        $traceableBuilder = $this->inner->flatten();

        if (!$traceableBuilder instanceof TraceableBuilder) {
            return $traceableBuilder;
        }

        $this->builders[] = ['flatten', $traceableBuilder];

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
