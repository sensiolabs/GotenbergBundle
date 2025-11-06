<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Chromium;

use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\Attributes\WithConfigurationNode;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\NodeBuilder\BooleanNodeBuilder;

/**
 * @package Behavior\\Performance
 */
trait PerformanceModeTrait
{
    abstract protected function getBodyBag(): BodyBag;

    /**
     * Gotenberg, by default, waits for the network idle event to ensure that the majority of the page is rendered during
     * conversion. However, this often significantly slows down the conversion process. Setting this form field to true
     * can greatly enhance the conversion speed.
     *
     * @see https://gotenberg.dev/docs/routes#performance-mode-chromium
     *
     * @example skipNetworkIdleEvent() // is same as `->skipNetworkIdleEvent(true)`
     */
    #[WithConfigurationNode(new BooleanNodeBuilder('skip_network_idle_event'))]
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
