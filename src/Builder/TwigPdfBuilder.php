<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Client\PdfResponse;
use Sensiolabs\GotenbergBundle\Enum\PdfPart;
use Sensiolabs\GotenbergBundle\Pdf\Gotenberg;
use Twig\Environment;

final class TwigPdfBuilder implements BuilderInterface
{
    use BuilderTrait;

    public const ENDPOINT = '/forms/chromium/convert/html';

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

    public function generate(): PdfResponse
    {
        return $this->gotenberg->generate($this);
    }
}
