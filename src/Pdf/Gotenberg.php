<?php

namespace Sensiolabs\GotenbergBundle\Pdf;

use Psr\Container\ContainerInterface;
use Sensiolabs\GotenbergBundle\Builder\HtmlPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\LibreOfficePdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\MarkdownPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\PdfBuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\UrlPdfBuilder;

final readonly class Gotenberg implements GotenbergInterface
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function get(string $builder): PdfBuilderInterface
    {
        return $this->container->get($builder);
    }

    public function html(): HtmlPdfBuilder
    {
        return $this->get(HtmlPdfBuilder::class);
    }

    public function url(): UrlPdfBuilder
    {
        return $this->get(UrlPdfBuilder::class);
    }

    public function office(): LibreOfficePdfBuilder
    {
        return $this->get(LibreOfficePdfBuilder::class);
    }

    public function markdown(): MarkdownPdfBuilder
    {
        return $this->get(MarkdownPdfBuilder::class);
    }
}
