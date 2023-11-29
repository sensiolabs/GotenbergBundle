<?php

namespace Sensiolabs\GotenbergBundle\Pdf;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\MarkdownPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\TwigPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\UrlPdfBuilder;
use Sensiolabs\GotenbergBundle\Client\PdfResponse;

interface GotenbergInterface
{
    public function generate(BuilderInterface $builder): PdfResponse;

    public function twig(): TwigPdfBuilder;

    public function url(): UrlPdfBuilder;

    public function markdown(): MarkdownPdfBuilder;
}
