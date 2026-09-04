<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Chromium;

use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\Attributes\WithConfigurationNode;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\Enumeration\Part;
use Sensiolabs\GotenbergBundle\Exception\PartRenderingException;
use Sensiolabs\GotenbergBundle\NodeBuilder\ArrayNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\ScalarNodeBuilder;

/**
 * @package Behavior\\Content
 */
trait PdfContentTrait
{
    use PartTrait;

    /**
     * @param string               $template #Template
     * @param array<string, mixed> $context
     *
     * @throws PartRenderingException if the template could not be rendered
     *
     * @example content('content.html.twig', ['my_var' => 'value'])
     */
    public function content(string $template, array $context = []): self
    {
        return $this->withRenderedPart(Part::Body, $template, $context);
    }

    /**
     * The raw html string to convert into PDF.
     *
     * Warning: Assets (css, images, etc...) cannot be parsed and loaded dynamically.
     * Assets can still be loaded using https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#assets.
     *
     * @example contentRaw('<html><body><h2>The content</h2></body></html>')
     */
    public function contentRaw(string $html): self
    {
        return $this->withRawPart(Part::Body, $html);
    }

    /**
     * The HTML file to convert into PDF.
     *
     * As assets files, by default the HTML files are fetch in the assets folder of your application.
     * If your HTML files are in another folder, you can override the default value of assets_directory in your
     * configuration file config/sensiolabs_gotenberg.yml.
     *
     * Warning: Assets (css, images, etc...) cannot be parsed and loaded dynamically.
     * Assets can still be loaded using https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#assets.
     *
     * @throws PartRenderingException if the template could not be rendered
     *
     * @example contentFile('../public/content.html')
     */
    public function contentFile(string $path): self
    {
        return $this->withFilePart(Part::Body, $path);
    }

    /**
     * @param string               $template #Template
     * @param array<string, mixed> $context
     *
     * @throws PartRenderingException if the template could not be rendered
     *
     * @see https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#header--footer
     *
     * @example header('header.html.twig', ['my_var' => 'value'])
     */
    #[WithConfigurationNode(new ArrayNodeBuilder('header', children: [
        new ScalarNodeBuilder('template', required: true, restrictTo: 'string'),
        new ArrayNodeBuilder('context', normalizeKeys: false, prototype: 'variable'),
    ]))]
    public function header(string $template, array $context = []): static
    {
        return $this->withRenderedPart(Part::Header, $template, $context);
    }

    /**
     * The raw html string to use as the page header.
     *
     * Warning: Assets (css, images, etc...) cannot be parsed and loaded dynamically.
     * Assets can still be loaded using https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#assets.
     *
     * @see https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#header--footer
     *
     * @example headerRaw('<html><body><h1>The header</h1></body></html>')
     */
    public function headerRaw(string $html): static
    {
        return $this->withRawPart(Part::Header, $html);
    }

    /**
     * @param string               $template #Template
     * @param array<string, mixed> $context
     *
     * @throws PartRenderingException if the template could not be rendered
     *
     * @see https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#header--footer
     *
     * @example footer('footer.html.twig', ['my_var' => 'value'])
     */
    #[WithConfigurationNode(new ArrayNodeBuilder('footer', children: [
        new ScalarNodeBuilder('template', required: true, restrictTo: 'string'),
        new ArrayNodeBuilder('context', normalizeKeys: false, prototype: 'variable'),
    ]))]
    public function footer(string $template, array $context = []): static
    {
        return $this->withRenderedPart(Part::Footer, $template, $context);
    }

    /**
     * The raw html string to use as the page footer.
     *
     * Warning: Assets (css, images, etc...) cannot be parsed and loaded dynamically.
     * Assets can still be loaded using https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#assets.
     *
     * @see https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#header--footer
     *
     * @example footerRaw('<html><body><h6>The footer</h6></body></html>')
     */
    public function footerRaw(string $html): static
    {
        return $this->withRawPart(Part::Footer, $html);
    }

    /**
     * HTML file containing the header.
     *
     * As assets files, by default the HTML files are fetch in the assets folder of your application.
     * If your HTML files are in another folder, you can override the default value of assets_directory in your
     * configuration file config/sensiolabs_gotenberg.yml.
     *
     * Warning: Assets (css, images, etc...) cannot be parsed and loaded dynamically.
     * Assets can still be loaded using https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#assets.
     *
     * @see https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#header--footer
     *
     * @throws PartRenderingException if the template could not be rendered
     *
     * @example headerFile('../templates/html/header.html')
     */
    public function headerFile(string $path): static
    {
        return $this->withFilePart(Part::Header, $path);
    }

    /**
     * HTML file containing the footer.
     *
     * As assets files, by default the HTML files are fetch in the assets folder of your application.
     * If your HTML files are in another folder, you can override the default value of assets_directory in your
     * configuration file config/sensiolabs_gotenberg.yml.
     *
     * Warning: Assets (css, images, etc...) cannot be parsed and loaded dynamically.
     * Assets can still be loaded using https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#assets.
     *
     * @see https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#header--footer
     *
     * @throws PartRenderingException if the template could not be rendered
     *
     * @example footerFile('../templates/html/footer.html')
     */
    public function footerFile(string $path): static
    {
        return $this->withFilePart(Part::Footer, $path);
    }

    #[NormalizeGotenbergPayload]
    private function normalizeContent(): \Generator
    {
        yield 'header.html' => NormalizerFactory::content();
        yield 'index.html' => NormalizerFactory::content();
        yield 'footer.html' => NormalizerFactory::content();
    }
}
