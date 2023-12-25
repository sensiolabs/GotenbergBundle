<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Client\PdfResponse;
use Sensiolabs\GotenbergBundle\Enum\PdfPart;
use Sensiolabs\GotenbergBundle\Pdf\GotenbergInterface;
use Twig\Environment;

final class TwigPdfBuilder implements BuilderInterface
{
    use BuilderTrait;

    private const ENDPOINT = '/forms/chromium/convert/html';

    public function __construct(private GotenbergInterface $gotenberg, private Environment|null $twig, private string $projectDir)
    {}

    public function getEndpoint(): string
    {
        return self::ENDPOINT;
    }

    /**
     * @param array<string, mixed> $context
     */
    public function content(string $path, array $context = []): self
    {
        $this->checkTwigDependency(__FUNCTION__);
        $this->addTwigTemplate($path, PdfPart::BodyPart, $context);
        return $this;
    }

    public function generate(): PdfResponse
    {
        return $this->gotenberg->generate($this);
    }
}
