<?php

namespace Sensiolabs\GotenbergBundle\Pdf;

use Sensiolabs\GotenbergBundle\Builder\HtmlPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\LibreOfficePdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\MarkdownPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\UrlPdfBuilder;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Twig\Environment;

final readonly class Gotenberg implements GotenbergInterface
{
    /**
     * @param array<string, mixed> $userConfigurations
     */
    public function __construct(
        private GotenbergClientInterface $gotenbergClient,
        private array $userConfigurations,
        private string $projectDir,
        private ?Environment $twig = null,
    ) {
    }

    public function html(): HtmlPdfBuilder
    {
        return (new HtmlPdfBuilder($this->gotenbergClient, $this->projectDir, $this->twig))
            ->setConfigurations($this->userConfigurations)
        ;
    }

    public function url(?string $url = null): UrlPdfBuilder
    {
        $builder = new UrlPdfBuilder($this->gotenbergClient, $this->projectDir, $this->twig);
        $builder->setConfigurations($this->userConfigurations);

        if (null !== $url) {
            $builder->url($url);
        }

        return $builder;
    }

    public function markdown(string ...$files): MarkdownPdfBuilder
    {
        return (new MarkdownPdfBuilder($this->gotenbergClient, $this->projectDir, $this->twig))
            ->setConfigurations($this->userConfigurations)
            ->files(...$files)
        ;
    }

    public function office(string ...$files): LibreOfficePdfBuilder
    {
        return (new LibreOfficePdfBuilder($this->gotenbergClient, $this->projectDir))
            ->setConfigurations($this->userConfigurations)
            ->files(...$files)
        ;
    }
}
