<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Enum\PdfPart;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Exception\PdfPartRenderingException;

final class HtmlPdfBuilder extends AbstractChromiumPdfBuilder
{
    private const ENDPOINT = '/forms/chromium/convert/html';

    /**
     * @param array<string, mixed> $context
     *
     * @throws PdfPartRenderingException if the template could not be rendered
     */
    public function content(string $template, array $context = []): self
    {
        return $this->withRenderedPart(PdfPart::BodyPart, $template, $context);
    }

    /**
     * The HTML file to convert into PDF.
     */
    public function contentFile(string $path): self
    {
        return $this->withPdfPartFile(PdfPart::BodyPart, $path);
    }

    public function getMultipartFormData(): array
    {
        if (!\array_key_exists(PdfPart::BodyPart->value, $this->formFields)) {
            throw new MissingRequiredFieldException('Content is required');
        }

        return parent::getMultipartFormData();
    }

    protected function getEndpoint(): string
    {
        return self::ENDPOINT;
    }
}
