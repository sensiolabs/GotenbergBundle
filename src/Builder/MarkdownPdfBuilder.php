<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Enum\PdfPart;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File as DataPartFile;
use Twig\Environment;

class MarkdownPdfBuilder extends AbstractChromiumPdfBuilder
{
    use TwigTrait;

    private const ENDPOINT = '/forms/chromium/convert/markdown';

    public function __construct(GotenbergClientInterface $gotenbergClient, string $projectDir, private readonly ?Environment $twig = null)
    {
        parent::__construct($gotenbergClient, $projectDir);
    }

    /**
     * The HTML file that wraps the markdown content.
     */
    public function htmlTemplate(string $filePath): self
    {
        $dataPart = new DataPart(new DataPartFile($this->resolveFilePath($filePath)), PdfPart::BodyPart->value);

        $this->formFields['htmlTemplate'] = $dataPart;

        return $this;
    }

    public function markdownFiles(string ...$paths): self
    {
        $this->formFields['markdownFiles'] = [];

        foreach ($paths as $path) {
            $this->addMarkdownFile($path);
        }

        return $this;
    }

    public function addMarkdownFile(string $path): self
    {
        $this->assertFileExtension($path, ['md']);

        $dataPart = new DataPart(new DataPartFile($this->resolveFilePath($path)));

        $this->formFields['markdownFiles'][$path] = $dataPart;

        return $this;
    }

    public function getEndpoint(): string
    {
        return self::ENDPOINT;
    }

    public function getMultipartFormData(): array
    {
        if (!\array_key_exists('htmlTemplate', $this->formFields) && !\array_key_exists(PdfPart::BodyPart->value, $this->formFields)) {
            throw new \RuntimeException('HTML template is required');
        }

        if ([] === ($this->formFields['markdownFiles'] ?? [])) {
            throw new \RuntimeException('At least one markdown file is required');
        }

        return parent::getMultipartFormData();
    }
}
