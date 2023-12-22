<?php

namespace Sensiolabs\GotenbergBundle\Builder;

interface UrlPdfBuilderInterface extends ChromiumPdfBuilderInterface, RenderingBuilderInterface
{
    /**
     * URL of the page you want to convert into PDF.
     */
    public function url(string $url): self;
}
