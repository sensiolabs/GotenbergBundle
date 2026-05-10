<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Chromium;

use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\Attributes\WithConfigurationNode;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\LoggerAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\NodeBuilder\BooleanNodeBuilder;

/**
 * @package Behavior\\Performance
 */
trait PerformanceModeTrait
{
    use LoggerAwareTrait;

    abstract protected function getBodyBag(): BodyBag;

    /**
     * Gotenberg, by default, waits for the network idle event to ensure that the majority of the page is rendered during
     * conversion. However, this often significantly slows down the conversion process. Setting this form field to true
     * can greatly enhance the conversion speed.
     *
     * @see https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#http--networking
     *
     * @example skipNetworkIdleEvent() // is same as `->skipNetworkIdleEvent(true)`
     */
    #[WithConfigurationNode(new BooleanNodeBuilder('skip_network_idle_event'))]
    public function skipNetworkIdleEvent(bool $bool = true): static
    {
        $this->getBodyBag()->set('skipNetworkIdleEvent', $bool);

        return $this;
    }

    /**
     * Does not wait for Chromium network to be almost idle (at most 2 open connections for 500ms) before conversion.
     * Useful for pages with long-polling or analytics connections. (default true).
     *
     * @see https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#http--networking
     *
     * @example skipNetworkAlmostIdleEvent() // is same as `->skipNetworkAlmostIdleEvent(true)`
     */
    #[WithConfigurationNode(new BooleanNodeBuilder('skip_network_almost_idle_event'))]
    public function skipNetworkAlmostIdleEvent(bool $bool = true): static
    {
        $this->logWarningIfVersionIs('<', '8.29', 'The option skipNetworkAlmostIdleEvent is not available.');

        $this->getBodyBag()->set('skipNetworkAlmostIdleEvent', $bool);

        return $this;
    }

    #[NormalizeGotenbergPayload]
    private function normalizePerformanceMode(): \Generator
    {
        yield 'skipNetworkIdleEvent' => NormalizerFactory::bool();
        yield 'skipNetworkAlmostIdleEvent' => NormalizerFactory::bool();
    }
}
