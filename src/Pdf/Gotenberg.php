<?php

namespace Sensiolabs\GotenbergBundle\Pdf;

use Sensiolabs\GotenbergBundle\Builder\HtmlPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\HtmlPdfBuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\LibreOfficePdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\LibreOfficePdfBuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\MarkdownPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\MarkdownPdfBuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\UrlPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\UrlPdfBuilderInterface;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Twig\Environment;

readonly class Gotenberg implements GotenbergInterface
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

    public function html(?string $contentFile = null): HtmlPdfBuilderInterface
    {
        $builder = new HtmlPdfBuilder($this->gotenbergClient, $this->projectDir, $this->twig);
        $builder->setConfigurations($this->userConfigurations);

        if (null !== $contentFile) {
            $builder->htmlContent($contentFile);
        }

        return $builder;
    }

    public function url(?string $url = null): UrlPdfBuilderInterface
    {
        $builder = new UrlPdfBuilder($this->gotenbergClient, $this->projectDir, $this->twig);
        $builder->setConfigurations($this->userConfigurations);

        if (null !== $url) {
            $builder->url($url);
        }

        return $builder;
    }

    public function markdown(?string $htmlTemplate = null, string ...$markdownFiles): MarkdownPdfBuilderInterface
    {
        $builder = new MarkdownPdfBuilder($this->gotenbergClient, $this->projectDir, $this->twig);
        $builder->setConfigurations($this->userConfigurations);

        if (null !== $htmlTemplate) {
            $builder->htmlTemplate($htmlTemplate);
        }

        $builder->markdownFiles(...$markdownFiles);

        return $builder;
    }

    public function office(string ...$officeFiles): LibreOfficePdfBuilderInterface
    {
        return (new LibreOfficePdfBuilder($this->gotenbergClient, $this->projectDir))
            ->setConfigurations($this->userConfigurations)
            ->officeFiles(...$officeFiles)
        ;
    }
}
