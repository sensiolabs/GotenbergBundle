<?php

namespace Sensiolabs\GotenbergBundle\Builder;

interface MarkdownPdfBuilderInterface extends ChromiumPdfBuilderInterface, RenderingBuilderInterface
{
    /**
     * The HTML file that wraps the markdown content.
     */
    public function htmlTemplate(string $filePath): self;

    public function markdownFiles(string ...$paths): self;

    public function addMarkdownFile(string $path): self;
}
