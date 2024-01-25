<?php

namespace Sensiolabs\GotenbergBundle\Pdf;

use Sensiolabs\GotenbergBundle\Builder\PdfBuilderInterface;

interface GotenbergInterface
{
    public function html(?string $contentFile = null): PdfBuilderInterface;

    public function url(?string $url = null): PdfBuilderInterface;

    public function markdown(?string $htmlTemplate = null, string ...$markdownFiles): PdfBuilderInterface;

    public function office(string ...$officeFiles): PdfBuilderInterface;
}
