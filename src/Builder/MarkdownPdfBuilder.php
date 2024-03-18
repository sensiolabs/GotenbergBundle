<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Enum\PdfPart;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Exception\PdfPartRenderingException;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File as DataPartFile;

final class MarkdownPdfBuilder extends AbstractChromiumPdfBuilder
{
    private const ENDPOINT = '/forms/chromium/convert/markdown';

    private bool $hasWrapper = false;

    private bool $hasMdFile = false;

    /**
     * The HTML file that wraps the markdown content, rendered from a Twig template.
     *
     * @param array<string, mixed> $context
     *
     * @throws PdfPartRenderingException if the template could not be rendered
     */
    public function twigWrapper(string $template, array $context = []): self
    {
        $this->hasWrapper = true;

        return $this->addTwigTemplate(PdfPart::BodyPart, $template, $context);
    }

    /**
     * The HTML file that wraps the markdown content.
     */
    public function htmlWrapper(string $path): self
    {
        $this->hasWrapper = true;

        return $this->addHtmlTemplate(PdfPart::BodyPart, $path);
    }

    public function files(string ...$paths): self
    {
        foreach ($paths as $path) {
            $this->assertFileExtension($path, ['md']);

            $dataPart = new DataPart(new DataPartFile($this->asset->resolve($path)));

            $this->multipartFormData[] = ['files' => $dataPart];
        }

        $this->hasMdFile = true;

        return $this;
    }

    public function getMultipartFormData(): array
    {
        if (!$this->hasWrapper) {
            throw new MissingRequiredFieldException('HTML template is required');
        }

        if (!$this->hasMdFile) {
            throw new MissingRequiredFieldException('At least one markdown file is required');
        }

        return parent::getMultipartFormData();
    }

    protected function getEndpoint(): string
    {
        return self::ENDPOINT;
    }
}
