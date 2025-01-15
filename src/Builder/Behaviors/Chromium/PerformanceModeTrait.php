<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Chromium;

use Sensiolabs\GotenbergBundle\Client\BodyBag;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * See https://gotenberg.dev/docs/routes#performance-mode-chromium.
 */
trait PerformanceModeTrait
{
    abstract protected function getBodyBag(): BodyBag;

    protected function configure(OptionsResolver $bodyOptionsResolver, OptionsResolver $headersOptionsResolver): void
    {
        $bodyOptionsResolver
            ->define('skipNetworkIdleEvent')
            ->info('Do not wait for Chromium network to be idle.')
            ->allowedTypes('bool')
        ;
    }

    public function skipNetworkIdleEvent(bool $bool = true): static
    {
        $this->getBodyBag()->set('skipNetworkIdleEvent', $bool);

        return $this;
    }
}