<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Client\PdfResponse;
use Sensiolabs\GotenbergBundle\Enum\PdfPart;
use Sensiolabs\GotenbergBundle\Pdf\Gotenberg;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Mime\Part\DataPart;
use Twig\Environment;

final class MarkdownPdfBuilder implements BuilderInterface
{
    use BuilderTrait;

    public const ENDPOINT = '/forms/chromium/convert/markdown';

    public function __construct(private Gotenberg $gotenberg, private Environment $twig, private string $projectDir)
    {}

    public function getEndpoint(): string
    {
        return self::ENDPOINT;
    }

    public function content(string $path, array $context = []): self
    {
        $this->addTwigTemplate($path, PdfPart::BodyPart, $context);
        return $this;
    }

    public function markdownFile(string $filePath): self
    {
        $this->fileExtensionChecker($filePath, 'md');

        return $this->addFile($filePath);
    }

    public function generate(): PdfResponse
    {
        $markdownFile = array_filter($this->multipartFormData, static function($formData) {
            return array_filter($formData, static function($data) {
                return $data instanceof DataPart && $data->getContentType() === 'text/markdown';
            });
        });

        if (count($markdownFile) !== 1) {
            throw new HttpException(400, 'Invalid request, a Markdown file is required with markdown method');
        }

        return $this->gotenberg->generate($this);
    }
}
