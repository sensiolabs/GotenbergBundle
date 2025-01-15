<?php

namespace Sensiolabs\GotenbergBundle;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\HtmlPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\MergePdfBuilder;
//use Sensiolabs\GotenbergBundle\BuilderOld\Pdf\ConvertPdfBuilder;
//use Sensiolabs\GotenbergBundle\BuilderOld\Pdf\HtmlPdfBuilder;
//use Sensiolabs\GotenbergBundle\BuilderOld\Pdf\LibreOfficePdfBuilder;
//use Sensiolabs\GotenbergBundle\BuilderOld\Pdf\MarkdownPdfBuilder;
//use Sensiolabs\GotenbergBundle\BuilderOld\Pdf\MergePdfBuilder;
//use Sensiolabs\GotenbergBundle\BuilderOld\Pdf\PdfBuilderInterface;
//use Sensiolabs\GotenbergBundle\Builder\Pdf\SplitPdfBuilder;
//use Sensiolabs\GotenbergBundle\BuilderOld\Pdf\UrlPdfBuilder;

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

 //    /**
 //     * @return UrlPdfBuilder
 //     */
 //    public function url(): PdfBuilderInterface;
 //
 //    /**
 //     * @return LibreOfficePdfBuilder
 //     */
 //    public function office(): PdfBuilderInterface;
 //
 //    /**
 //     * @return MarkdownPdfBuilder
 //     */
 //    public function markdown(): PdfBuilderInterface;

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
