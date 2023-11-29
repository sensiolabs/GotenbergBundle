<?php

namespace Sensiolabs\GotenbergBundle\Pdf;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\MarkdownPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\OfficePdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\TwigPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\UrlPdfBuilder;
use Sensiolabs\GotenbergBundle\Client\GotenbergClient;
use Sensiolabs\GotenbergBundle\Client\PdfResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Twig\Environment;

class Gotenberg implements GotenbergInterface
{
    public function __construct(private GotenbergClient $gotenbergClient, private Environment $twig, private array $userConfigurations, private string $projectDir)
    {}

    public function generate(BuilderInterface $builder): PdfResponse
    {
        $response = $this->sendRequest($builder);

        if (200 !== $response->getStatusCode()) {
            throw new HttpException($response->getStatusCode(), $response->getContent());
        }

        return new PdfResponse($response);
    }

    public function twig(): TwigPdfBuilder
    {
        return (new TwigPdfBuilder($this, $this->twig, $this->projectDir))
            ->setConfigurations($this->userConfigurations)
        ;
    }

    public function url(): UrlPdfBuilder
    {
        return (new UrlPdfBuilder($this, $this->twig, $this->projectDir))
            ->setConfigurations($this->userConfigurations)
        ;
    }

    public function markdown(): MarkdownPdfBuilder
    {
        return (new MarkdownPdfBuilder($this, $this->twig, $this->projectDir))
            ->setConfigurations($this->userConfigurations)
        ;
    }

    public function office(): OfficePdfBuilder
    {
        return (new OfficePdfBuilder($this, $this->twig, $this->projectDir))
            ->setConfigurations($this->userConfigurations)
        ;
    }

    private function sendRequest(BuilderInterface $builder): ResponseInterface
    {
        return $this->gotenbergClient->post($builder);
    }
}
