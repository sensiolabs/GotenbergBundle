<?php

namespace Sensiolabs\GotenbergBundle;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Pdf\HtmlPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\LibreOfficePdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\MarkdownPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\MergePdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\UrlPdfBuilder;
use Sensiolabs\GotenbergBundle\BuilderOld\Pdf\PdfBuilderInterface;

interface GotenbergPdfInterface
{
    /**
     * @template T of PdfBuilderInterface
     *
     * @param string|class-string<T> $builder
     *
     * @return ($builder is class-string ? T : PdfBuilderInterface)
     */
    public function get(string $builder): BuilderInterface;

    /**
     * @return HtmlPdfBuilder
     */
    public function html(): BuilderInterface;

    /**
     * @return UrlPdfBuilder
     */
    public function url(): BuilderInterface;

    /**
     * @return MarkdownPdfBuilder
     */
    public function markdown(): BuilderInterface;

    /**
     * @return LibreOfficePdfBuilder
     */
    public function office(): BuilderInterface;

    /**
     * @return MergePdfBuilder
     */
    public function merge(): BuilderInterface;

    //    /**
    //     * @return ConvertPdfBuilder
    //     */
    //    public function convert(): PdfBuilderInterface;
    //
    //    /**
    //     * @return SplitPdfBuilder
    //     */
    //    public function split(): PdfBuilderInterface;
}
