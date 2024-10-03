<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Chromium;

use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\AssetBaseDirFormatterAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\TwigAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\ValueObject\RenderedPart;
use Sensiolabs\GotenbergBundle\Client\BodyBag;
use Sensiolabs\GotenbergBundle\Enumeration\Part;
use Sensiolabs\GotenbergBundle\Exception\PdfPartRenderingException;
use Sensiolabs\GotenbergBundle\Twig\GotenbergAssetRuntime;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * See https://gotenberg.dev/docs/routes#header-footer-chromium.
 */
trait HeaderFooterTrait
{
    use AssetBaseDirFormatterAwareTrait;
    use TwigAwareTrait;

    abstract protected function getBodyBag(): BodyBag;

    protected function configure(OptionsResolver $bodyOptionsResolver, OptionsResolver $headersOptionsResolver): void
    {
        foreach (Part::cases() as $part) {
            $bodyOptionsResolver
                ->define($part->value)
                ->allowedTypes(RenderedPart::class, 'string')
            ;
        }
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
    protected function withRenderedPart(Part $part, string $template, array $context = []): static
    {
        $this->getBodyBag()->set($part->value, function () use ($part, $template, $context): RenderedPart {
            $this->getTwig()->getRuntime(GotenbergAssetRuntime::class)->setBuilder($this);
            try {
                $renderedPart = new RenderedPart($part, $this->getTwig()->render($template, array_merge($context, ['_builder' => $this])));
            } catch (\Throwable $t) {
                throw new PdfPartRenderingException(\sprintf('Could not render template "%s" into PDF part "%s". %s', $template, $part->value, $t->getMessage()), previous: $t);
            } finally {
                $this->getTwig()->getRuntime(GotenbergAssetRuntime::class)->setBuilder(null);
            }

            return $renderedPart;
        });

        return $this;
    }

    protected function withFilePart(Part $part, string $path): static
    {
        $this->getBodyBag()->set($part->value, new \SplFileInfo($this->getAssetBaseDirFormatter()->resolve($path)));

        return $this;
    }
}
