<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Enum\PdfPart;
use Sensiolabs\GotenbergBundle\Exception\PdfPartRenderingException;

interface RenderingBuilderInterface
{
    /**
     * @param array<string, mixed> $context
     *
     * @throws PdfPartRenderingException if the template could not be rendered
     */
    public function renderPart(PdfPart $pdfPart, string $template, array $context = []): self;
}
