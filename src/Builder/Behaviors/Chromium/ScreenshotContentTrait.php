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
trait ScreenshotContentTrait
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
     * The raw html string to convert into a screenshot.
     *
     * Warning: Assets (css, images, etc...) cannot be parsed and loaded dynamically.
     * Assets can still be loaded using https://gotenberg.dev/docs/convert-with-chromium/screenshot-html#assets.
     *
     * @example contentRaw('<html><body><h2>The content</h2></body></html>')
     */
    public function contentRaw(string $html): self
    {
        return $this->withRawPart(Part::Body, $html);
    }

    /**
     * The HTML file to convert into a screenshot.
     *
     * As assets files, by default the HTML files are fetch in the assets folder of your application.
     * If your HTML files are in another folder, you can override the default value of assets_directory in your
     * configuration file config/sensiolabs_gotenberg.yml.
     *
     * Warning: Assets (css, images, etc...) cannot be parsed and loaded dynamically.
     * Assets can still be loaded using https://gotenberg.dev/docs/convert-with-chromium/screenshot-html#assets.
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
     * The Twig template to use as the page header.
     *
     * @deprecated since sensiolabs/gotenberg-bundle 1.5, Gotenberg does not read headers on screenshot routes. It will be removed in 2.0.
     *
     * @param string               $template #Template
     * @param array<string, mixed> $context
     *
     * @throws PartRenderingException if the template could not be rendered
     */
    #[WithConfigurationNode(new ArrayNodeBuilder('header', children: [
        new ScalarNodeBuilder('template', required: true, restrictTo: 'string'),
        new ArrayNodeBuilder('context', normalizeKeys: false, prototype: 'variable'),
    ]))]
    public function header(string $template, array $context = []): static
    {
        trigger_deprecation('sensiolabs/gotenberg-bundle', '1.5', 'Calling "%s::%s()" is deprecated, Gotenberg does not read headers on screenshot routes. It will be removed in 2.0.', static::class, __FUNCTION__);

        return $this->withRenderedPart(Part::Header, $template, $context);
    }

    /**
     * The raw html string to use as the page header.
     *
     * @deprecated since sensiolabs/gotenberg-bundle 1.5, Gotenberg does not read headers on screenshot routes. It will be removed in 2.0.
     */
    public function headerRaw(string $html): static
    {
        trigger_deprecation('sensiolabs/gotenberg-bundle', '1.5', 'Calling "%s::%s()" is deprecated, Gotenberg does not read headers on screenshot routes. It will be removed in 2.0.', static::class, __FUNCTION__);

        return $this->withRawPart(Part::Header, $html);
    }

    /**
     * HTML file containing the header.
     *
     * @deprecated since sensiolabs/gotenberg-bundle 1.5, Gotenberg does not read headers on screenshot routes. It will be removed in 2.0.
     *
     * @throws PartRenderingException if the template could not be rendered
     */
    public function headerFile(string $path): static
    {
        trigger_deprecation('sensiolabs/gotenberg-bundle', '1.5', 'Calling "%s::%s()" is deprecated, Gotenberg does not read headers on screenshot routes. It will be removed in 2.0.', static::class, __FUNCTION__);

        return $this->withFilePart(Part::Header, $path);
    }

    /**
     * The Twig template to use as the page footer.
     *
     * @deprecated since sensiolabs/gotenberg-bundle 1.5, Gotenberg does not read footers on screenshot routes. It will be removed in 2.0.
     *
     * @param string               $template #Template
     * @param array<string, mixed> $context
     *
     * @throws PartRenderingException if the template could not be rendered
     */
    #[WithConfigurationNode(new ArrayNodeBuilder('footer', children: [
        new ScalarNodeBuilder('template', required: true, restrictTo: 'string'),
        new ArrayNodeBuilder('context', normalizeKeys: false, prototype: 'variable'),
    ]))]
    public function footer(string $template, array $context = []): static
    {
        trigger_deprecation('sensiolabs/gotenberg-bundle', '1.5', 'Calling "%s::%s()" is deprecated, Gotenberg does not read footers on screenshot routes. It will be removed in 2.0.', static::class, __FUNCTION__);

        return $this->withRenderedPart(Part::Footer, $template, $context);
    }

    /**
     * The raw html string to use as the page footer.
     *
     * @deprecated since sensiolabs/gotenberg-bundle 1.5, Gotenberg does not read footers on screenshot routes. It will be removed in 2.0.
     */
    public function footerRaw(string $html): static
    {
        trigger_deprecation('sensiolabs/gotenberg-bundle', '1.5', 'Calling "%s::%s()" is deprecated, Gotenberg does not read footers on screenshot routes. It will be removed in 2.0.', static::class, __FUNCTION__);

        return $this->withRawPart(Part::Footer, $html);
    }

    /**
     * HTML file containing the footer.
     *
     * @deprecated since sensiolabs/gotenberg-bundle 1.5, Gotenberg does not read footers on screenshot routes. It will be removed in 2.0.
     *
     * @throws PartRenderingException if the template could not be rendered
     */
    public function footerFile(string $path): static
    {
        trigger_deprecation('sensiolabs/gotenberg-bundle', '1.5', 'Calling "%s::%s()" is deprecated, Gotenberg does not read footers on screenshot routes. It will be removed in 2.0.', static::class, __FUNCTION__);

        return $this->withFilePart(Part::Footer, $path);
    }

    #[NormalizeGotenbergPayload]
    private function normalizeContent(): \Generator
    {
        // header.html and footer.html are deprecated since 1.5: the screenshot routes ignore
        // both parts. They must keep a content() normalizer nonetheless — without one the raw
        // RenderedPart falls back to NormalizerFactory::noop() and the payload changes. The
        // yield order is irrelevant: the multipart order comes from BodyBag insertion.
        yield 'header.html' => NormalizerFactory::content();
        yield 'index.html' => NormalizerFactory::content();
        yield 'footer.html' => NormalizerFactory::content();
    }
}
