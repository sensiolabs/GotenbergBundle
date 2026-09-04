<?php

namespace Sensiolabs\GotenbergBundle\Builder\Screenshot;

use Sensiolabs\GotenbergBundle\Builder\AbstractBuilder;
use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\Attributes\WithBuilderConfiguration;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\ChromiumScreenshotTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\RequestContextAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\BuilderAssetInterface;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\Enumeration\Part;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Exception\PartRenderingException;

/**
 * You may have the possibility to generate a screenshot from a URL.
 *
 * @see https://gotenberg.dev/docs/convert-with-chromium/screenshot-url
 */
#[WithBuilderConfiguration(type: 'screenshot', name: 'url')]
final class UrlScreenshotBuilder extends AbstractBuilder implements BuilderAssetInterface
{
    use ChromiumScreenshotTrait {
        content as private;
        contentFile as private;
        contentRaw as private;
    }
    use RequestContextAwareTrait;

    public const ENDPOINT = '/forms/chromium/screenshot/url';

    /**
     * URL of the page you want to convert into a screenshot.
     *
     * @see https://gotenberg.dev/docs/convert-with-chromium/screenshot-url
     *
     * @example url('https://sensiolabs.com/fr/')
     */
    public function url(string $url): self
    {
        $this->getBodyBag()->set('url', $url);

        return $this;
    }

    /**
     * Route of the page you want to convert into PDF.
     *
     * You must provide a URL accessible by Gotenberg with a public Host.
     * Or configure request_context.base_uri in sensiolabs_gotenberg.yaml
     *
     * @param string       $name       #Route
     * @param array<mixed> $parameters
     *
     * @example route('home', ['my_var' => 'value'])
     */
    public function route(string $name, array $parameters = []): self
    {
        $this->getBodyBag()->set('route', [$name, $parameters]);

        return $this;
    }

    /**
     * @deprecated since sensiolabs/gotenberg-bundle 1.5, the page body comes from the URL, use "url()" or "route()" instead. It will be removed in 2.0.
     *
     * @param string               $template #Template
     * @param array<string, mixed> $context
     *
     * @throws PartRenderingException if the template could not be rendered
     */
    public function content(string $template, array $context = []): self
    {
        trigger_deprecation('sensiolabs/gotenberg-bundle', '1.5', 'Calling "%s()" is deprecated, the page body comes from the URL. Use "url()" or "route()" instead. It will be removed in 2.0.', __METHOD__);

        return $this->withRenderedPart(Part::Body, $template, $context);
    }

    /**
     * @deprecated since sensiolabs/gotenberg-bundle 1.5, the page body comes from the URL, use "url()" or "route()" instead. It will be removed in 2.0.
     */
    public function contentRaw(string $html): self
    {
        trigger_deprecation('sensiolabs/gotenberg-bundle', '1.5', 'Calling "%s()" is deprecated, the page body comes from the URL. Use "url()" or "route()" instead. It will be removed in 2.0.', __METHOD__);

        return $this->withRawPart(Part::Body, $html);
    }

    /**
     * @deprecated since sensiolabs/gotenberg-bundle 1.5, the page body comes from the URL, use "url()" or "route()" instead. It will be removed in 2.0.
     *
     * @throws PartRenderingException if the file could not be found
     */
    public function contentFile(string $path): self
    {
        trigger_deprecation('sensiolabs/gotenberg-bundle', '1.5', 'Calling "%s()" is deprecated, the page body comes from the URL. Use "url()" or "route()" instead. It will be removed in 2.0.', __METHOD__);

        return $this->withFilePart(Part::Body, $path);
    }

    protected function getEndpoint(): string
    {
        return self::ENDPOINT;
    }

    protected function validatePayloadBody(): void
    {
        if ($this->getBodyBag()->get('url') === null && $this->getBodyBag()->get('route') === null) {
            throw new MissingRequiredFieldException('"url" (or "route") is required');
        }

        if ($this->getBodyBag()->get('url') !== null && $this->getBodyBag()->get('route') !== null) {
            throw new MissingRequiredFieldException('Provide only one of ["route", "url"] parameter. Not both.');
        }
    }

    #[NormalizeGotenbergPayload]
    private function normalizeRoute(): \Generator
    {
        yield 'route' => NormalizerFactory::route($this->getRequestContext(), $this->getUrlGenerator());
    }
}
