<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Chromium;

use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\Attributes\WithConfigurationNode;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\LoggerAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\NodeBuilder\ArrayNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\ScalarNodeBuilder;

/**
 * @package Behavior\\EmulatedMediaFeatures
 */
trait EmulatedMediaFeaturesTrait
{
    use LoggerAwareTrait;

    abstract protected function getBodyBag(): BodyBag;

    /**
     * You can simulate specific browser conditions by overriding CSS media features.
     * This is particularly useful for forcing "Dark Mode" or testing layouts with reduced motion.
     *
     * @param list<array{name: string, value: string}> $emulatedMediaFeatures
     *
     * @see https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#emulated-media-features
     *
     * @example emulatedMediaFeatures([['name' => 'prefers-color-scheme', 'value' => 'dark'], ['name' => 'prefers-reduced-motion', 'value' => 'reduce'])
     */
    #[WithConfigurationNode(new ArrayNodeBuilder('emulated_media_features', prototype: 'array', children: [
        new ScalarNodeBuilder('name', required: true, restrictTo: 'string'),
        new ScalarNodeBuilder('value', required: true, restrictTo: 'string'),
    ]))]
    public function emulatedMediaFeatures(array $emulatedMediaFeatures): static
    {
        $this->logWarningIfVersionIs('<', '8.27', 'The option emulatedMediaFeatures is not available.');

        if ([] === $emulatedMediaFeatures) {
            $this->getBodyBag()->unset('emulatedMediaFeatures');

            return $this;
        }

        $this->getBodyBag()->set('emulatedMediaFeatures', $emulatedMediaFeatures);

        return $this;
    }

    #[NormalizeGotenbergPayload]
    private function normalizeEmulatedMedia(): \Generator
    {
        yield 'emulatedMediaFeatures' => NormalizerFactory::json();
    }
}
