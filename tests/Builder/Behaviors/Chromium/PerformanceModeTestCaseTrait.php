<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\Chromium;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\BehaviorTrait;

/**
 * @template T of BuilderInterface
 */
trait PerformanceModeTestCaseTrait
{
    /** @use BehaviorTrait<T> */
    use BehaviorTrait;

    abstract protected function assertGotenbergFormData(string $field, string $expectedValue): void;

    public function testWaitForChromiumNetworkToBeIdle(): void
    {
        $this->getDefaultBuilder()
            ->skipNetworkIdleEvent()
            ->generate()
        ;

        $this->assertGotenbergFormData('skipNetworkIdleEvent', 'true');
    }
}
