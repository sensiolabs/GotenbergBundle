<?php

namespace Sensiolabs\GotenbergBundle;

use Psr\Container\ContainerInterface;
use Sensiolabs\GotenbergBundle\Builder\Pdf\HtmlPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\LibreOfficePdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\MarkdownPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\UrlPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\PdfBuilderInterface;

final readonly class GotenbergPdf implements GotenbergPdfInterface
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function get(string $builder): PdfBuilderInterface
    {
        return $this->container->get($builder);
    }

    /**
     * @param 'html'|'url'|'markdown'|'office' $key
     *
     * @return HtmlPdfBuilder     $key is 'url' ? UrlPdfBuilder :
     *     $key is 'office' ? LibreOfficePdfBuilder :
     *     $key is 'markdown' ? MarkdownPdfBuilder :
     *      PdfBuilderInterface
     * )
     */
    private function getInternal(string $key): PdfBuilderInterface
    {
        return $this->get(".sensiolabs_gotenberg.builder.{$key}");
    }

    public function html(): HtmlPdfBuilder
    {
        return $this->getInternal('html');
    }

    public function url(): UrlPdfBuilder
    {
        return $this->getInternal('url');
    }

    public function office(): LibreOfficePdfBuilder
    {
        return $this->getInternal('office');
    }

    public function markdown(): MarkdownPdfBuilder
    {
        return $this->getInternal('markdown');
    }
}
