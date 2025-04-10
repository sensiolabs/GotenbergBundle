<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Chromium;

use Sensiolabs\GotenbergBundle\Builder\Attributes\ExposeSemantic;
use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\AssetBaseDirFormatterAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\TwigAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\Builder\ValueObject\RenderedPart;
use Sensiolabs\GotenbergBundle\Enumeration\Part;
use Sensiolabs\GotenbergBundle\Exception\PdfPartRenderingException;
use Sensiolabs\GotenbergBundle\NodeBuilder\ArrayNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\ScalarNodeBuilder;
use Sensiolabs\GotenbergBundle\Twig\GotenbergRuntime;

trait ContentTrait
{
    use AssetBaseDirFormatterAwareTrait;
    use TwigAwareTrait;

    abstract protected function getBodyBag(): BodyBag;

    /**
     * @param string               $template #Template
     * @param array<string, mixed> $context
     *
     * @throws PdfPartRenderingException if the template could not be rendered
     */
    public function content(string $template, array $context = []): self
    {
        return $this->withRenderedPart(Part::Body, $template, $context);
    }

    /**
     * The HTML file to convert into PDF.
     */
    public function contentFile(string $path): self
    {
        return $this->withFilePart(Part::Body, $path);
    }

    /**
     * @param string               $template #Template
     * @param array<string, mixed> $context
     *
     * @throws PdfPartRenderingException if the template could not be rendered
     *
     * See https://gotenberg.dev/docs/routes#header-footer-chromium.
     */
    #[ExposeSemantic(new ArrayNodeBuilder('header', children: [
        new ScalarNodeBuilder('template', required: true, restrictTo: 'string'),
        new ArrayNodeBuilder('context', normalizeKeys: false, prototype: 'variable'),
    ]))]
    public function header(string $template, array $context = []): static
    {
        return $this->withRenderedPart(Part::Header, $template, $context);
    }

    /**
     * @param string               $template #Template
     * @param array<string, mixed> $context
     *
     * @throws PdfPartRenderingException if the template could not be rendered
     *
     * See https://gotenberg.dev/docs/routes#header-footer-chromium.
     */
    #[ExposeSemantic(new ArrayNodeBuilder('footer', children: [
        new ScalarNodeBuilder('template', required: true, restrictTo: 'string'),
        new ArrayNodeBuilder('context', normalizeKeys: false, prototype: 'variable'),
    ]))]
    public function footer(string $template, array $context = []): static
    {
        return $this->withRenderedPart(Part::Footer, $template, $context);
    }

    /**
     * HTML file containing the header.
     */
    public function headerFile(string $path): static
    {
        return $this->withFilePart(Part::Header, $path);
    }

    /**
     * HTML file containing the footer.
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
        $this->getTwig()->getRuntime(GotenbergRuntime::class)->setBuilder($this);
        try {
            $renderedPart = new RenderedPart($part, $this->getTwig()->render($template, array_merge($context, ['_builder' => $this])));
        } catch (\Throwable $t) {
            throw new PdfPartRenderingException(\sprintf('Could not render template "%s" into PDF part "%s". %s', $template, $part->value, $t->getMessage()), previous: $t);
        } finally {
            $this->getTwig()->getRuntime(GotenbergRuntime::class)->setBuilder(null);
        }

        $this->getBodyBag()->set($part->value, $renderedPart);

        return $this;
    }

    protected function withFilePart(Part $part, string $path): static
    {
        $this->getBodyBag()->set($part->value, new \SplFileInfo($this->getAssetBaseDirFormatter()->resolve($path)));

        return $this;
    }

    #[NormalizeGotenbergPayload]
    private function normalizeContent(): \Generator
    {
        yield 'header.html' => NormalizerFactory::content();
        yield 'index.html' => NormalizerFactory::content();
        yield 'footer.html' => NormalizerFactory::content();
    }
}
