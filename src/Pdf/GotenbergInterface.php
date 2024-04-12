<?php

namespace Sensiolabs\GotenbergBundle\Pdf;

use Sensiolabs\GotenbergBundle\Builder\HtmlPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\LibreOfficePdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\MarkdownPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\PdfBuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\UrlPdfBuilder;

interface GotenbergInterface
{
    /**
     * @template T of PdfBuilderInterface
     *
     * @param string|class-string<T> $builder
     *
     * @return ($builder is class-string<T> ? T : PdfBuilderInterface)
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
}
