<?php

namespace Sensiolabs\GotenbergBundle;

use Psr\Container\ContainerInterface;
use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Pdf\ConvertPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\FlattenPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\HtmlPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\LibreOfficePdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\MarkdownPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\MergePdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\SplitPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\UrlPdfBuilder;

final class GotenbergPdf implements GotenbergPdfInterface
{
    public function __construct(
        private readonly ContainerInterface $container,
    ) {
    }

    public function get(string $builder): BuilderInterface
    {
        return $this->container->get($builder);
    }

    /**
     * @param 'html'|'url'|'markdown'|'office'|'merge'|'convert'|'split'|'flatten' $key
     *
     * @return (
     *   $key is 'html' ? HtmlPdfBuilder :
     *   $key is 'url' ? UrlPdfBuilder :
     *   $key is 'markdown' ? MarkdownPdfBuilder :
     *   $key is 'office' ? LibreOfficePdfBuilder :
     *   $key is 'merge' ? MergePdfBuilder :
     *   $key is 'convert' ? ConvertPdfBuilder :
     *   $key is 'split' ? SplitPdfBuilder :
     *   $key is 'flatten' ? FlattenPdfBuilder :
     *   BuilderInterface
     * )
     */
    private function getInternal(string $key): BuilderInterface
    {
        return $this->get(".sensiolabs_gotenberg.pdf_builder.{$key}");
    }

    public function html(): BuilderInterface
    {
        return $this->getInternal('html');
    }

    public function url(): BuilderInterface
    {
        return $this->getInternal('url');
    }

    public function office(): BuilderInterface
    {
        return $this->getInternal('office');
    }

    public function markdown(): BuilderInterface
    {
        return $this->getInternal('markdown');
    }

    public function merge(): BuilderInterface
    {
        return $this->getInternal('merge');
    }

    public function convert(): BuilderInterface
    {
        return $this->getInternal('convert');
    }

    public function split(): BuilderInterface
    {
        return $this->getInternal('split');
    }

    public function flatten(): BuilderInterface
    {
        return $this->getInternal('flatten');
    }
}
