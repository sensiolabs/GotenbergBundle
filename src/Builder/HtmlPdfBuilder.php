<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Enum\PdfPart;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Exception\PdfPartRenderingException;

final class HtmlPdfBuilder extends AbstractChromiumPdfBuilder
{
    private const ENDPOINT = '/forms/chromium/convert/html';

    private bool $hasContent = false;

    /**
     * @param array<string, mixed> $context
     *
     * @throws PdfPartRenderingException if the template could not be rendered
     */
    public function twigContent(string $template, array $context = []): self
    {
        $this->hasContent = true;

        return $this->addTwigTemplate(PdfPart::BodyPart, $template, $context);
    }

    /**
     * The HTML file to convert into PDF.
     */
    public function htmlContent(string $path): self
    {
        $this->hasContent = true;

        return $this->addHtmlTemplate(PdfPart::BodyPart, $path);
    }

    public function getMultipartFormData(): array
    {
        if (!$this->hasContent) {
            throw new MissingRequiredFieldException('Content is required');
        }

        return parent::getMultipartFormData();
    }

    protected function getEndpoint(): string
    {
        return self::ENDPOINT;
    }
}
