<?php

namespace Sensiolabs\GotenbergBundle\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\AbstractBuilder;
use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\Attributes\SemanticNode;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\ChromiumTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\WebhookTrait;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Symfony\Component\Routing\RequestContext;

#[SemanticNode('url')]
class UrlPdfBuilder extends AbstractBuilder
{
    use ChromiumTrait;

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
        return '/forms/chromium/convert/url';
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
        yield 'route' => NormalizerFactory::route($this->requestContext, $this->getUrlGenerator());
    }
}
