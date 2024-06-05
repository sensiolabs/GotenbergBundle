<?php

namespace Sensiolabs\GotenbergBundle\Builder\Screenshot;

use Sensiolabs\GotenbergBundle\Enumeration\Part;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Exception\ScreenshotPartRenderingException;

final class HtmlScreenshotBuilder extends AbstractChromiumScreenshotBuilder
{
    private const ENDPOINT = '/forms/chromium/screenshot/html';

    /**
     * @param string               $template #Template
     * @param array<string, mixed> $context
     *
     * @throws ScreenshotPartRenderingException if the template could not be rendered
     */
    public function content(string $template, array $context = []): self
    {
        return $this->withRenderedPart(Part::Body, $template, $context);
    }

    /**
     * The HTML file to convert into Screenshot.
     */
    public function contentFile(string $path): self
    {
        return $this->withScreenshotPartFile(Part::Body, $path);
    }

    public function getMultipartFormData(): array
    {
        if (!\array_key_exists(Part::Body->value, $this->formFields)) {
            throw new MissingRequiredFieldException('Content is required');
        }

        return parent::getMultipartFormData();
    }

    protected function getEndpoint(): string
    {
        return self::ENDPOINT;
    }
}
