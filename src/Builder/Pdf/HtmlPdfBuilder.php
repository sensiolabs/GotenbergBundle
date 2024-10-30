<?php

namespace Sensiolabs\GotenbergBundle\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Enumeration\Part;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Exception\PdfPartRenderingException;

final class HtmlPdfBuilder extends AbstractChromiumPdfBuilder
{
    private const ENDPOINT = '/forms/chromium/convert/html';

    /**
     * @param string               $template #Template
     * @param array<string, mixed> $context
     *
     * @throws PdfPartRenderingException if the template could not be rendered
     */
    public function content(string $template, array $context = []): self
    {
        return $this->withRenderedPart(Part::Body, $template, $context);
    }

    /**
     * The HTML file to convert into PDF.
     */
    public function contentFile(string $path): self
    {
        return $this->withPdfPartFile(Part::Body, $path);
    }

    public function getMultipartFormData(): array
    {
        if (!\array_key_exists(Part::Body->value, $this->formFields) && [] === ($this->formFields['downloadFrom'] ?? [])) {
            throw new MissingRequiredFieldException('Content is required');
        }

        return parent::getMultipartFormData();
    }

    protected function getEndpoint(): string
    {
        return self::ENDPOINT;
    }
}
