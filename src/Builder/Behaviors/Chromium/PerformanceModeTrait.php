<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Chromium;

use Sensiolabs\GotenbergBundle\Builder\Attributes\ExposeSemantic;
use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\Enumeration\NodeType;

/**
 * See https://gotenberg.dev/docs/routes#performance-mode-chromium.
 */
trait PerformanceModeTrait
{
    abstract protected function getBodyBag(): BodyBag;

    #[ExposeSemantic('skip_network_idle_event', NodeType::Boolean, ['default_null' => true])]
    public function skipNetworkIdleEvent(bool $bool = true): static
    {
        $this->getBodyBag()->set('skipNetworkIdleEvent', $bool);

        return $this;
    }

    #[NormalizeGotenbergPayload]
    protected function normalizePerformanceMode(): \Generator
    {
        yield 'skipNetworkIdleEvent' => NormalizerFactory::bool();
    }
}
