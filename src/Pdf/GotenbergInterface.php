<?php

namespace Sensiolabs\GotenbergBundle\Pdf;

use Sensiolabs\GotenbergBundle\Builder\HtmlPdfBuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\LibreOfficePdfBuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\MarkdownPdfBuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\UrlPdfBuilderInterface;

interface GotenbergInterface
{
    public function html(?string $contentFile = null): HtmlPdfBuilderInterface;

    public function url(?string $url = null): UrlPdfBuilderInterface;

    public function markdown(?string $htmlTemplate = null, string ...$markdownFiles): MarkdownPdfBuilderInterface;

    public function office(string ...$officeFiles): LibreOfficePdfBuilderInterface;
}
