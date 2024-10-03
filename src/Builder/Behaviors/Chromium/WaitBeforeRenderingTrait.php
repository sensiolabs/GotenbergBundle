<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Chromium;

use Sensiolabs\GotenbergBundle\Client\BodyBag;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @see https://gotenberg.dev/docs/routes#wait-before-rendering-chromium.
 */
trait WaitBeforeRenderingTrait
{
    abstract protected function getBodyBag(): BodyBag;

    protected function configure(OptionsResolver $bodyOptionsResolver, OptionsResolver $headersOptionsResolver): void
    {
        $bodyOptionsResolver
            ->setDefined(['waitDelay', 'waitForExpression'])
            ->setAllowedTypes('waitDelay', ['string'])
            ->setAllowedValues('waitDelay', fn (mixed $value): bool => 1 === preg_match('/^\d+(s|ms)$/', $value))
            ->setAllowedTypes('waitForExpression', ['string'])
        ;
    }

    /**
     * Sets the duration (i.e., "1s", "2ms", etc.) to wait when loading an HTML
     * document before converting it to PDF. (default None).
     *
     * @see https://gotenberg.dev/docs/routes#wait-before-rendering-chromium
     */
    public function waitDelay(string $delay): static
    {
        $this->getBodyBag()->set('waitDelay', $delay);

        return $this;
    }

    /**
     * Sets the JavaScript expression to wait before converting an HTML
     * document to PDF until it returns true. (default None).
     *
     * For instance: "window.status === 'ready'".
     *
     * @see https://gotenberg.dev/docs/routes#wait-before-rendering
     */
    public function waitForExpression(string $expression): static
    {
        $this->getBodyBag()->set('waitForExpression', $expression);

        return $this;
    }
}