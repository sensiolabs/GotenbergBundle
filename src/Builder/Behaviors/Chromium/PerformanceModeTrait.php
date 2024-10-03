<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Chromium;

use Sensiolabs\GotenbergBundle\Builder\Attributes\ExposeSemantic;
use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\NodeBuilder\BooleanNodeBuilder;

/**
 * See https://gotenberg.dev/docs/routes#performance-mode-chromium.
 */
trait PerformanceModeTrait
{
    abstract protected function getBodyBag(): BodyBag;

    #[ExposeSemantic(new BooleanNodeBuilder('skip_network_idle_event'))]
    public function skipNetworkIdleEvent(bool $bool = true): static
    {
        $this->getBodyBag()->set('skipNetworkIdleEvent', $bool);

        return $this;
    }

    #[NormalizeGotenbergPayload]
    private function normalizePerformanceMode(): \Generator
    {
        yield 'skipNetworkIdleEvent' => NormalizerFactory::bool();
    }
}
