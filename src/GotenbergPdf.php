<?php

namespace Sensiolabs\GotenbergBundle;

use Psr\Container\ContainerInterface;
use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Pdf\HtmlPdfBuilder;

//use Sensiolabs\GotenbergBundle\BuilderOld\Pdf\ConvertPdfBuilder;
//use Sensiolabs\GotenbergBundle\BuilderOld\Pdf\HtmlPdfBuilder;
//use Sensiolabs\GotenbergBundle\BuilderOld\Pdf\LibreOfficePdfBuilder;
//use Sensiolabs\GotenbergBundle\BuilderOld\Pdf\MarkdownPdfBuilder;
//use Sensiolabs\GotenbergBundle\BuilderOld\Pdf\MergePdfBuilder;
//use Sensiolabs\GotenbergBundle\BuilderOld\Pdf\SplitPdfBuilder;
//use Sensiolabs\GotenbergBundle\BuilderOld\Pdf\UrlPdfBuilder;

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
     * @param 'html'|'url'|'markdown'|'office'|'merge'|'convert'|'split' $key
     *
     * @return (
     *   $key is 'html' ? HtmlPdfBuilder :
     *   $key is 'url' ? UrlPdfBuilder :
     *   $key is 'markdown' ? MarkdownPdfBuilder :
     *   $key is 'office' ? LibreOfficePdfBuilder :
     *   $key is 'merge' ? MergePdfBuilder :
     *   $key is 'convert' ? ConvertPdfBuilder :
     *   $key is 'split' ? SplitPdfBuilder :
     *   PdfBuilderInterface
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

//    public function url(): PdfBuilderInterface
//    {
//        return $this->getInternal('url');
//    }
//
//    public function office(): PdfBuilderInterface
//    {
//        return $this->getInternal('office');
//    }
//
//    public function markdown(): PdfBuilderInterface
//    {
//        return $this->getInternal('markdown');
//    }

    public function merge(): BuilderInterface
    {
        return $this->getInternal('merge');
    }

//    public function convert(): PdfBuilderInterface
//    {
//        return $this->getInternal('convert');
//    }
//
//    public function split(): PdfBuilderInterface
//    {
//        return $this->getInternal('split');
//    }
}
