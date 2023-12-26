<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Client\PdfResponse;
use Sensiolabs\GotenbergBundle\Enum\PdfPart;
use Sensiolabs\GotenbergBundle\Pdf\GotenbergInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Twig\Environment;

final class TwigPdfBuilder implements BuilderInterface
{
    use BuilderTrait;

    private const ENDPOINT = '/forms/chromium/convert/html';

    public function __construct(private GotenbergInterface $gotenberg, private string $projectDir, private ?Environment $twig)
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
        if (!$this->twig instanceof Environment) {
            throw new ServiceNotFoundException('twig', msg: sprintf('Twig is required to use this method "%s". Try to run "composer require symfony/twig-bundle"', __FUNCTION__));
        }

        $this->addTwigTemplate($path, PdfPart::BodyPart, $context);
        return $this;
    }

    public function generate(): PdfResponse
    {
        return $this->gotenberg->generate($this);
    }
}
