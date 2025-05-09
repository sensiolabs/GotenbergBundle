<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\Chromium;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Enumeration\EmulatedMediaType;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\BehaviorTrait;

/**
 * @template T of BuilderInterface
 */
trait EmulatedMediaTypeTestCaseTrait
{
    /** @use BehaviorTrait<T> */
    use BehaviorTrait;

    abstract protected function assertGotenbergFormData(string $field, string $expectedValue): void;

    public function testSetEmulatedMediaTypeWithPrintMediaType(): void
    {
        $this->getDefaultBuilder()
            ->emulatedMediaType(EmulatedMediaType::Print)
            ->generate()
        ;

        $this->assertGotenbergFormData('emulatedMediaType', EmulatedMediaType::Print->value);
    }
}
