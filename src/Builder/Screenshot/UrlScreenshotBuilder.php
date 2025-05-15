<?php

namespace Sensiolabs\GotenbergBundle\Builder\Screenshot;

use Sensiolabs\GotenbergBundle\Builder\AbstractBuilder;
use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\Attributes\SemanticNode;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\ChromiumScreenshotTrait;
use Sensiolabs\GotenbergBundle\Builder\BuilderAssetInterface;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Symfony\Component\Routing\RequestContext;
use Symfony\Contracts\Service\Attribute\SubscribedService;
use Symfony\Contracts\Service\ServiceSubscriberTrait;

#[SemanticNode(type: 'screenshot', name: 'url')]
final class UrlScreenshotBuilder extends AbstractBuilder implements BuilderAssetInterface
{
    use ChromiumScreenshotTrait;
    use ServiceSubscriberTrait;

    public const ENDPOINT = '/forms/chromium/screenshot/url';

    /**
     * URL of the page you want to convert into a screenshot.
     *
     * @see https://gotenberg.dev/docs/routes#screenshots-route
     * @see https://gotenberg.dev/docs/routes#url-into-pdf-route
     */
    public function url(string $url): self
    {
        $this->getBodyBag()->set('url', $url);

        return $this;
    }

    /**
     * @param string       $name       #Route
     * @param array<mixed> $parameters
     *
     * @see https://gotenberg.dev/docs/routes#screenshots-route
     * @see https://gotenberg.dev/docs/routes#url-into-pdf-route
     */
    public function route(string $name, array $parameters = []): self
    {
        $this->getBodyBag()->set('route', [$name, $parameters]);

        return $this;
    }

    #[SubscribedService('.sensiolabs_gotenberg.request_context', nullable: true)]
    protected function getRequestContext(): RequestContext|null
    {
        if (
            !$this->container->has('.sensiolabs_gotenberg.request_context')
            || !($requestContext = $this->container->get('.sensiolabs_gotenberg.request_context')) instanceof RequestContext
        ) {
            return null;
        }

        return $requestContext;
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
