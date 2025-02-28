<?php

declare(strict_types=1);

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;

/**
 * @template T of BuilderInterface
 */
trait FlattenTestCaseTrait
{
    /** @use BehaviorTrait<T> */
    use BehaviorTrait;

    abstract protected function assertGotenbergFormData(string $field, string $expectedValue): void;

    public function testFlattenContentsIntoSinglePdf(): void
    {
        $this->getDefaultBuilder()
            ->flatten()
            ->generate()
        ;

        $this->assertGotenbergFormData('flatten', 'true');
    }
}
