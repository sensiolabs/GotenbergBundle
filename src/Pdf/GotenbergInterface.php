<?php

namespace Sensiolabs\GotenbergBundle\Pdf;

use Sensiolabs\GotenbergBundle\Builder\PdfBuilderInterface;

interface GotenbergInterface
{
    public function html(): PdfBuilderInterface;

    public function url(?string $url = null): PdfBuilderInterface;

    public function markdown(string ...$files): PdfBuilderInterface;

    public function office(string ...$files): PdfBuilderInterface;
}
