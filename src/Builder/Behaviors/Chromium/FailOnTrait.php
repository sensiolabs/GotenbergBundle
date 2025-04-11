<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Chromium;

use Sensiolabs\GotenbergBundle\Builder\Attributes\ExposeSemantic;
use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\NodeBuilder\ArrayNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\BooleanNodeBuilder;

trait FailOnTrait
{
    abstract protected function getBodyBag(): BodyBag;

    /**
     * Return a 409 Conflict response if the HTTP status code from
     * the main page is not acceptable. (default [499,599]). (overrides any previous configuration).
     *
     * @see https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium
     *
     * @param array<int, int> $statusCodes
     */
    #[ExposeSemantic(new ArrayNodeBuilder('fail_on_http_status_codes', prototype: 'integer'))]
    public function failOnHttpStatusCodes(array $statusCodes): static
    {
        $this->getBodyBag()->set('failOnHttpStatusCodes', $statusCodes);

        return $this;
    }

    /**
     * Return a 409 Conflict response if the HTTP status code from at least one resource is not acceptable. (overrides any previous configuration).
     *
     * @see https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium
     *
     * @param list<int<100, 599>> $statusCodes
     */
    #[ExposeSemantic(new ArrayNodeBuilder('fail_on_resource_http_status_codes', prototype: 'integer'))]
    public function failOnResourceHttpStatusCodes(array $statusCodes): static
    {
        $this->getBodyBag()->set('failOnResourceHttpStatusCodes', $statusCodes);

        return $this;
    }

    /**
     * Forces GotenbergPdf to return a 409 Conflict response if Chromium fails to load at least one resource.
     * (default false).
     *
     * @see https://gotenberg.dev/docs/routes#network-errors-chromium
     */
    #[ExposeSemantic(new BooleanNodeBuilder('fail_on_resource_loading_failed'))]
    public function failOnResourceLoadingFailed(bool $bool = true): static
    {
        $this->getBodyBag()->set('failOnResourceLoadingFailed', $bool);

        return $this;
    }

    /**
     * Forces GotenbergPdf to return a 409 Conflict response if there are
     * exceptions in the Chromium console. (default false).
     *
     * @see https://gotenberg.dev/docs/routes#console-exceptions
     */
    #[ExposeSemantic(new BooleanNodeBuilder('fail_on_console_exceptions'))]
    public function failOnConsoleExceptions(bool $bool = true): static
    {
        $this->getBodyBag()->set('failOnConsoleExceptions', $bool);

        return $this;
    }

    #[NormalizeGotenbergPayload]
    private function normalizeFailOn(): \Generator
    {
        yield 'failOnHttpStatusCodes' => NormalizerFactory::json(false);
        yield 'failOnResourceHttpStatusCodes' => NormalizerFactory::json(false);
        yield 'failOnResourceLoadingFailed' => NormalizerFactory::bool();
        yield 'failOnConsoleExceptions' => NormalizerFactory::bool();
    }
}
