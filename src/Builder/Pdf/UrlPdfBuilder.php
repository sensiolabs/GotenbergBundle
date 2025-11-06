<?php

namespace Sensiolabs\GotenbergBundle\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\AbstractBuilder;
use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\Attributes\WithBuilderConfiguration;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\ChromiumPdfTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\RequestContextAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\BuilderAssetInterface;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;

/**
 * You may have the possibility to generate a PDF from a URL.
 *
 * @see https://gotenberg.dev/docs/routes#url-into-pdf-route
 */
#[WithBuilderConfiguration(type: 'pdf', name: 'url')]
final class UrlPdfBuilder extends AbstractBuilder implements BuilderAssetInterface
{
    use ChromiumPdfTrait;
    use RequestContextAwareTrait;

    public const ENDPOINT = '/forms/chromium/convert/url';

    /**
     * URL of the page you want to convert into PDF.
     *
     * @see https://gotenberg.dev/docs/routes#url-into-pdf-route
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
     * @see https://gotenberg.dev/docs/routes#url-into-pdf-route
     *
     * @example route('home', ['my_var' => 'value'])
     */
    public function route(string $name, array $parameters = []): self
    {
        $this->getBodyBag()->set('route', [$name, $parameters]);

        return $this;
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

    /**
     * @internal
     */
    #[NormalizeGotenbergPayload]
    private function normalizeRoute(): \Generator
    {
        yield 'route' => NormalizerFactory::route($this->getRequestContext(), $this->getUrlGenerator());
    }
}
