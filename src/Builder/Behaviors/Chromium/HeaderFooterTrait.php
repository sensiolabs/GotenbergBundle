<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Chromium;

use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\TwigAwareTrait;
use Sensiolabs\GotenbergBundle\Client\BodyBag;
use Sensiolabs\GotenbergBundle\Enumeration\Part;
use Sensiolabs\GotenbergBundle\Exception\PdfPartRenderingException;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File as DataPartFile;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * See https://gotenberg.dev/docs/routes#header-footer-chromium.
 */
trait HeaderFooterTrait
{
    use TwigAwareTrait;

    abstract protected function getBodyBag(): BodyBag;

    protected function configure(OptionsResolver $bodyOptionsResolver, OptionsResolver $headersOptionsResolver): void
    {
        $bodyOptionsResolver
            ->setDefined(
                array_map(fn (Part $p): string => $p->value, Part::cases()),
            )
        ;
    }

    /**
     * @param string               $template #Template
     * @param array<string, mixed> $context
     *
     * @throws PdfPartRenderingException if the template could not be rendered
     */
    public function header(string $template, array $context = []): static
    {
        return $this->withRenderedPart(Part::Header, $template, $context);
    }

    /**
     * @param string               $template #Template
     * @param array<string, mixed> $context
     *
     * @throws PdfPartRenderingException if the template could not be rendered
     */
    public function footer(string $template, array $context = []): static
    {
        return $this->withRenderedPart(Part::Footer, $template, $context);
    }

    /**
     * HTML file containing the header. (default None).
     */
    public function headerFile(string $path): static
    {
        return $this->withFilePart(Part::Header, $path);
    }

    /**
     * HTML file containing the footer. (default None).
     */
    public function footerFile(string $path): static
    {
        return $this->withFilePart(Part::Footer, $path);
    }

    /**
     * @param string               $template #Template
     * @param array<string, mixed> $context
     *
     * @throws PdfPartRenderingException if the template could not be rendered
     */
    protected function withRenderedPart(Part $pdfPart, string $template, array $context = []): static
    {
        try {
            $html = $this->getTwig()->render($template, array_merge($context, ['_builder' => $this]));
        } catch (\Throwable $error) {
            throw new PdfPartRenderingException(\sprintf('Could not render template "%s" into PDF part "%s". %s', $template, $pdfPart->value, $error->getMessage()), previous: $error);
        }

        $this->getBodyBag()->set($pdfPart->value, new DataPart($html, $pdfPart->value, 'text/html'));

        return $this;
    }

    protected function withFilePart(Part $part, string $path): static
    {
        $this->getBodyBag()->set($part->value, new DataPart(new DataPartFile($this->asset->resolve($path)), $part->value));

        return $this;
    }
}
