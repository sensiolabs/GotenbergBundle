<?php

namespace Sensiolabs\GotenbergBundle\Builder;

interface HtmlPdfBuilderInterface extends ChromiumPdfBuilderInterface, RenderingBuilderInterface
{
    /**
     * The HTML file to convert into PDF.
     */
    public function htmlContent(string $filePath): self;
}
