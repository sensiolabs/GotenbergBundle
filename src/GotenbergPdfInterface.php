<?php

namespace Sensiolabs\GotenbergBundle;

use Sensiolabs\GotenbergBundle\Builder\Pdf\HtmlPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\LibreOfficePdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\MarkdownPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\PdfBuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Pdf\UrlPdfBuilder;

interface GotenbergPdfInterface
{
    /**
     * @template T of PdfBuilderInterface
     *
     * @param string|class-string<T> $builder
     */
    public function get(string $builder): PdfBuilderInterface;

    public function html(): HtmlPdfBuilder;

    public function url(): UrlPdfBuilder;

    public function office(): LibreOfficePdfBuilder;

    public function markdown(): MarkdownPdfBuilder;
}
