<?php

namespace Sensiolabs\GotenbergBundle\Debug;

use Sensiolabs\GotenbergBundle\Builder\Pdf\ConvertPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\HtmlPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\LibreOfficePdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\MarkdownPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\MergePdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\PdfBuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Pdf\UrlPdfBuilder;
use Sensiolabs\GotenbergBundle\Debug\Builder\TraceablePdfBuilder;
use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

final class TraceableGotenbergPdf implements GotenbergPdfInterface
{
    /**
     * @var list<array{string, TraceablePdfBuilder}>
     */
    private array $builders = [];

    public function __construct(
        private readonly GotenbergPdfInterface $inner,
    ) {
    }

    public function get(string $builder): PdfBuilderInterface
    {
        $traceableBuilder = $this->inner->get($builder);

        if (!$traceableBuilder instanceof TraceablePdfBuilder) {
            return $traceableBuilder;
        }

        $this->builders[] = [$builder, $traceableBuilder];

        return $traceableBuilder;
    }

    /**
     * @return HtmlPdfBuilder|TraceablePdfBuilder
     */
    public function html(): PdfBuilderInterface
    {
        /** @var HtmlPdfBuilder|TraceablePdfBuilder $traceableBuilder */
        $traceableBuilder = $this->inner->html();

        if (!$traceableBuilder instanceof TraceablePdfBuilder) {
            return $traceableBuilder;
        }

        $this->builders[] = ['html', $traceableBuilder];

        return $traceableBuilder;
    }

    /**
     * @return UrlPdfBuilder|TraceablePdfBuilder
     */
    public function url(): PdfBuilderInterface
    {
        /** @var UrlPdfBuilder|TraceablePdfBuilder $traceableBuilder */
        $traceableBuilder = $this->inner->url();

        if (!$traceableBuilder instanceof TraceablePdfBuilder) {
            return $traceableBuilder;
        }

        $this->builders[] = ['url', $traceableBuilder];

        return $traceableBuilder;
    }

    /**
     * @return LibreOfficePdfBuilder|TraceablePdfBuilder
     */
    public function office(): PdfBuilderInterface
    {
        /** @var LibreOfficePdfBuilder|TraceablePdfBuilder $traceableBuilder */
        $traceableBuilder = $this->inner->office();

        if (!$traceableBuilder instanceof TraceablePdfBuilder) {
            return $traceableBuilder;
        }

        $this->builders[] = ['office', $traceableBuilder];

        return $traceableBuilder;
    }

    /**
     * @return MarkdownPdfBuilder|TraceablePdfBuilder
     */
    public function markdown(): PdfBuilderInterface
    {
        /** @var MarkdownPdfBuilder|TraceablePdfBuilder $traceableBuilder */
        $traceableBuilder = $this->inner->markdown();

        if (!$traceableBuilder instanceof TraceablePdfBuilder) {
            return $traceableBuilder;
        }

        $this->builders[] = ['markdown', $traceableBuilder];

        return $traceableBuilder;
    }

    /**
     * @return MergePdfBuilder|TraceablePdfBuilder
     */
    public function merge(): PdfBuilderInterface
    {
        /** @var MergePdfBuilder|TraceablePdfBuilder $traceableBuilder */
        $traceableBuilder = $this->inner->merge();

        if (!$traceableBuilder instanceof TraceablePdfBuilder) {
            return $traceableBuilder;
        }

        $this->builders[] = ['merge', $traceableBuilder];

        return $traceableBuilder;
    }

    /**
     * @return ConvertPdfBuilder|TraceablePdfBuilder
     */
    public function convert(): PdfBuilderInterface
    {
        /** @var ConvertPdfBuilder|TraceablePdfBuilder $traceableBuilder */
        $traceableBuilder = $this->inner->convert();

        if (!$traceableBuilder instanceof TraceablePdfBuilder) {
            return $traceableBuilder;
        }

        $this->builders[] = ['convert', $traceableBuilder];

        return $traceableBuilder;
    }

    /**
     * @return list<array{string, TraceablePdfBuilder}>
     */
    public function getBuilders(): array
    {
        return $this->builders;
    }
}
