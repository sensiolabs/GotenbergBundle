<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Enum\PdfPart;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File as DataPartFile;
use Twig\Environment;

class HtmlPdfBuilder extends AbstractChromiumPdfBuilder
{
    use TwigTrait;

    private const ENDPOINT = '/forms/chromium/convert/html';

    public function __construct(
        GotenbergClientInterface $gotenbergClient,
        string $projectDir,
        private readonly ?Environment $twig = null,
    ) {
        parent::__construct($gotenbergClient, $projectDir);
    }

    /**
     * The HTML file to convert into PDF.
     */
    public function htmlContent(string $filePath): self
    {
        $dataPart = new DataPart(new DataPartFile($this->resolveFilePath($filePath)), PdfPart::BodyPart->value);

        $this->formFields['htmlContent'] = $dataPart;

        return $this;
    }

    public function getEndpoint(): string
    {
        return self::ENDPOINT;
    }

    public function getMultipartFormData(): array
    {
        if (!\array_key_exists('htmlContent', $this->formFields) && !\array_key_exists(PdfPart::BodyPart->value, $this->formFields)) {
            throw new \RuntimeException('HTML content is required');
        }

        return parent::getMultipartFormData();
    }
}
