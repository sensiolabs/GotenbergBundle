<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\Chromium;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\BehaviorTrait;

/**
 * @template T of BuilderInterface
 */
trait WaitBeforeRenderingTestCaseTrait
{
    /** @use BehaviorTrait<T> */
    use BehaviorTrait;

    abstract protected function assertGotenbergFormData(string $field, string $expectedValue): void;

    public function testWaitDurationBeforeConverting(): void
    {
        $this->getDefaultBuilder()
            ->waitDelay('2s')
            ->generate()
        ;

        $this->assertGotenbergFormData('waitDelay', '2s');
    }

    public function testSetWaitForExpression(): void
    {
        $this->getDefaultBuilder()
            ->waitForExpression("window.status === 'ready'")
            ->generate()
        ;

        $this->assertGotenbergFormData('waitForExpression', "window.status === 'ready'");
    }
}
