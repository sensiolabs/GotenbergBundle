<?php

namespace Sensiolabs\GotenbergBundle\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\AbstractBuilder;
use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\Attributes\SemanticNode;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\ChromiumPdfTrait;
use Sensiolabs\GotenbergBundle\Builder\BuilderAssetInterface;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Symfony\Component\Routing\RequestContext;

/**
 * @see https://gotenberg.dev/docs/routes#url-into-pdf-route
 */
#[SemanticNode('url')]
final class UrlPdfBuilder extends AbstractBuilder implements BuilderAssetInterface
{
    use ChromiumPdfTrait;

    public const ENDPOINT = '/forms/chromium/convert/url';

    private RequestContext|null $requestContext = null;

    /**
     * URL of the page you want to convert into PDF.
     */
    public function url(string $url): self
    {
        $this->getBodyBag()->set('url', $url);

        return $this;
    }

    /**
     * @param string       $name       #Route
     * @param array<mixed> $parameters
     */
    public function route(string $name, array $parameters = []): self
    {
        $this->getBodyBag()->set('route', [$name, $parameters]);

        return $this;
    }

    public function setRequestContext(RequestContext|null $requestContext = null): self
    {
        $this->requestContext = $requestContext;

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
        yield 'route' => NormalizerFactory::route($this->requestContext, $this->getUrlGenerator());
    }

    public static function type(): string
    {
        return 'pdf';
    }
}
