<?php

namespace Sensiolabs\GotenbergBundle;

use Sensiolabs\GotenbergBundle\Builder\Pdf\ConvertPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\HtmlPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\LibreOfficePdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\MarkdownPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\MergePdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\PdfBuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Pdf\SplitPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\UrlPdfBuilder;

interface GotenbergPdfInterface
{
    /**
     * @template T of PdfBuilderInterface
     *
     * @param string|class-string<T> $builder
     *
     * @return ($builder is class-string ? T : PdfBuilderInterface)
     */
    public function get(string $builder): PdfBuilderInterface;

    /**
     * @return HtmlPdfBuilder
     */
    public function html(): PdfBuilderInterface;

    /**
     * @return UrlPdfBuilder
     */
    public function url(): PdfBuilderInterface;

    /**
     * @return LibreOfficePdfBuilder
     */
    public function office(): PdfBuilderInterface;

    /**
     * @return MarkdownPdfBuilder
     */
    public function markdown(): PdfBuilderInterface;

    /**
     * @return MergePdfBuilder
     */
    public function merge(): PdfBuilderInterface;

    /**
     * @return ConvertPdfBuilder
     */
    public function convert(): PdfBuilderInterface;

    /**
     * @return SplitPdfBuilder
     */
    public function split(): PdfBuilderInterface;
}
