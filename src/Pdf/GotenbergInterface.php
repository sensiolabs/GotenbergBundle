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
     * @param class-string<T> $builder
     *
     * @return T
     */
    public function get(string $builder): PdfBuilderInterface;

    public function html(): HtmlPdfBuilder;
    public function url(): UrlPdfBuilder;
    public function office(): LibreOfficePdfBuilder;
    public function markdown(): MarkdownPdfBuilder;
}
