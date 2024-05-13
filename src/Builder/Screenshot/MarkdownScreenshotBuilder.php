<?php

namespace Sensiolabs\GotenbergBundle\Builder\Screenshot;

use Sensiolabs\GotenbergBundle\Builder\Pdf\AbstractChromiumPdfBuilder;
use Sensiolabs\GotenbergBundle\Enum\PdfPart;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Exception\PdfPartRenderingException;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File as DataPartFile;

final class MarkdownScreenshotBuilder extends AbstractChromiumScreenshotBuilder
{
    private const ENDPOINT = '/forms/chromium/convert/markdown';

    /**
     * The HTML file that wraps the markdown content, rendered from a Twig template.
     *
     * @param string               $template #Template
     * @param array<string, mixed> $context
     *
     * @throws PdfPartRenderingException if the template could not be rendered
     */
    public function wrapper(string $template, array $context = []): self
    {
        return $this->withRenderedPart(PdfPart::BodyPart, $template, $context);
    }

    /**
     * The HTML file that wraps the markdown content.
     */
    public function wrapperFile(string $path): self
    {
        return $this->withPdfPartFile(PdfPart::BodyPart, $path);
    }

    public function files(string ...$paths): self
    {
        $this->formFields['files'] = [];

        foreach ($paths as $path) {
            $this->assertFileExtension($path, ['md']);

            $dataPart = new DataPart(new DataPartFile($this->asset->resolve($path)));

            $this->formFields['files'][$path] = $dataPart;
        }

        return $this;
    }

    public function getMultipartFormData(): array
    {
        if (!\array_key_exists(PdfPart::BodyPart->value, $this->formFields)) {
            throw new MissingRequiredFieldException('HTML template is required');
        }

        if ([] === ($this->formFields['files'] ?? [])) {
            throw new MissingRequiredFieldException('At least one markdown file is required');
        }

        return parent::getMultipartFormData();
    }

    protected function getEndpoint(): string
    {
        return self::ENDPOINT;
    }
}
