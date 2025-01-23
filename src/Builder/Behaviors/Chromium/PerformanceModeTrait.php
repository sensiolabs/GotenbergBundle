<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Chromium;

use Sensiolabs\GotenbergBundle\Builder\BodyBag;

/**
 * See https://gotenberg.dev/docs/routes#performance-mode-chromium.
 */
trait PerformanceModeTrait
{
    abstract protected function getBodyBag(): BodyBag;


    public function skipNetworkIdleEvent(bool $bool = true): static
    {
        $this->getBodyBag()->set('skipNetworkIdleEvent', $bool);

        return $this;
    }
}
