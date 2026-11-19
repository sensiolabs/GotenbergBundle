<?php

declare(strict_types=1);

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;

/**
 * @template T of BuilderInterface
 */
trait OptimizeImagesTestCaseTrait
{
    /** @use BehaviorTrait<T> */
    use BehaviorTrait;

    /** @use ImageQualityTestCaseTrait<T> */
    use ImageQualityTestCaseTrait;

    abstract protected function assertGotenbergFormData(string $field, string $expectedValue): void;

    public function testOptimizeImages(): void
    {
        $this->getDefaultBuilder()
            ->optimizeImages()
            ->imageQuality(60)
            ->generate()
        ;

        $this->assertGotenbergFormData('optimizeImages', 'true');
        $this->assertGotenbergFormData('imageQuality', '60');
    }
}
