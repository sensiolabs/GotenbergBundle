<?php

namespace Sensiolabs\GotenbergBundle;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Pdf\HtmlPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\LibreOfficePdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\MarkdownPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\UrlPdfBuilder;

interface GotenbergPdfInterface
{
    /**
     * @template T of BuilderInterface
     *
     * @param string|class-string<T> $builder
     *
     * @return BuilderInterface
     */
    public function get(string $builder);

    public function html(): HtmlPdfBuilder;

    public function url(): UrlPdfBuilder;

    public function office(): LibreOfficePdfBuilder;

    public function markdown(): MarkdownPdfBuilder;
}
