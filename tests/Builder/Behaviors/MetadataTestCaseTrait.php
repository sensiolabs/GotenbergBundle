<?php

declare(strict_types=1);

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;

/**
 * @template T of BuilderInterface
 */
trait MetadataTestCaseTrait
{
    /** @use BehaviorTrait<T> */
    use BehaviorTrait;

    abstract protected function assertGotenbergFormData(string $field, string $expectedValue): void;

    public function testSetMetadataWithArray(): void
    {
        $this->getDefaultBuilder()
            ->metadata([
                'Author' => 'SensioLabs',
                'Creator' => 'SensioLabs',
                'Title' => 'GotenbergBundle',
            ])
            ->generate()
        ;

        $this->assertGotenbergFormData('metadata', '{"Author":"SensioLabs","Creator":"SensioLabs","Title":"GotenbergBundle"}');
    }

    public function testAddMetadataToExistingMetadata(): void
    {
        $this->getDefaultBuilder()
            ->metadata([
                'Author' => 'SensioLabs',
                'Creator' => 'SensioLabs',
                'Title' => 'GotenbergBundle',
            ])
            ->addMetadata('Title', 'MyBundle')
            ->generate()
        ;

        $this->assertGotenbergFormData('metadata', '{"Title":"MyBundle","Author":"SensioLabs","Creator":"SensioLabs"}');
    }
}
