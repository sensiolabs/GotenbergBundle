<?php

namespace Sensiolabs\GotenbergBundle\Pdf;

use Sensiolabs\GotenbergBundle\Builder\PdfBuilderInterface;

interface GotenbergInterface
{
    public function html(): PdfBuilderInterface;

    public function url(): PdfBuilderInterface;

    public function markdown(): PdfBuilderInterface;

    public function office(): PdfBuilderInterface;
}
