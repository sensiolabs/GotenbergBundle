<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\Chromium;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\BehaviorTrait;

/**
 * @template T of BuilderInterface
 */
trait EmulatedMediaFeaturesTestCaseTrait
{
    /** @use BehaviorTrait<T> */
    use BehaviorTrait;

    abstract protected function assertGotenbergFormData(string $field, string $expectedValue): void;

    public function testSetEmulatedMediaFeatures(): void
    {
        $this->getDefaultBuilder()
            ->emulatedMediaFeatures([
                ['name' => 'prefers-color-scheme', 'value' => 'dark'],
                ['name' => 'prefers-reduced-motion', 'value' => 'reduce'],
                ['name' => 'color-gamut', 'value' => 'rec2020'],
                ['name' => 'forced-colors', 'value' => 'active'],
            ])
            ->generate()
        ;

        $this->assertGotenbergFormData('emulatedMediaFeatures', '[{"name":"prefers-color-scheme","value":"dark"},{"name":"prefers-reduced-motion","value":"reduce"},{"name":"color-gamut","value":"rec2020"},{"name":"forced-colors","value":"active"}]');
    }
}
