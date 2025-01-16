<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Chromium;

use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\Client\BodyBag;
use Sensiolabs\GotenbergBundle\Exception\JsonEncodingException;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

trait FailOnTrait
{
    abstract protected function getBodyBag(): BodyBag;

    protected function configure(OptionsResolver $bodyOptionsResolver, OptionsResolver $headersOptionsResolver): void
    {
        $bodyOptionsResolver
            ->define('failOnHttpStatusCodes')
            ->info('Return a 409 Conflict response if the HTTP status code from the main page is not acceptable.')
            ->allowedTypes('int[]')
            ->normalize(NormalizerFactory::json(false))
        ;

        $bodyOptionsResolver
            ->define('failOnResourceHttpStatusCodes')
            ->info('Return a 409 Conflict response if the HTTP status code from at least one resource is not acceptable.')
            ->allowedTypes('int[]')
            ->normalize(NormalizerFactory::json(false))
        ;

        $bodyOptionsResolver
            ->define('failOnResourceLoadingFailed')
            ->info('Return a 409 Conflict response if Chromium fails to load at least one resource.')
            ->allowedTypes('bool')
        ;

        $bodyOptionsResolver
            ->define('failOnConsoleExceptions')
            ->info('Return a 409 Conflict response if there are exceptions in the Chromium console.')
            ->allowedTypes('bool')
        ;
    }

    /**
     * Return a 409 Conflict response if the HTTP status code from
     * the main page is not acceptable. (default [499,599]). (overrides any previous configuration).
     *
     * @see https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium
     *
     * @param array<int, int> $statusCodes
     */
    public function failOnHttpStatusCodes(array $statusCodes): static
    {
        $this->getBodyBag()->set('failOnHttpStatusCodes', $statusCodes);

        return $this;
    }

    /**
     * Return a 409 Conflict response if the HTTP status code from at least one resource is not acceptable.
     * (default None). (overrides any previous configuration).
     *
     * @see https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium
     *
     * @param list<int<100, 599>> $statusCodes
     */
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
    public function failOnConsoleExceptions(bool $bool = true): static
    {
        $this->getBodyBag()->set('failOnConsoleExceptions', $bool);

        return $this;
    }
}
