<?php

declare(strict_types=1);

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;

/**
 * @template T of BuilderInterface
 */
trait ImageQualityTestCaseTrait
{
    /** @use BehaviorTrait<T> */
    use BehaviorTrait;

    abstract protected function assertGotenbergFormData(string $field, string $expectedValue): void;

    public function testImageQuality(): void
    {
        $this->getDefaultBuilder()
            ->imageQuality(60)
            ->generate()
        ;

        $this->assertGotenbergFormData('imageQuality', '60');
    }

    public function testUnsetImageQuality(): void
    {
        $builder = $this->getDefaultBuilder()
            ->imageQuality(60)
        ;

        self::assertArrayHasKey('imageQuality', $builder->getBodyBag()->all());

        $builder->imageQuality(null);
        self::assertArrayNotHasKey('imageQuality', $builder->getBodyBag()->all());
    }

    public function testImageQualityTooLow(): void
    {
        $this->expectException(InvalidBuilderConfiguration::class);
        $this->expectExceptionMessage('ImageQuality value "0" must be between 1 and 100.');

        $this->getDefaultBuilder()->imageQuality(0); // @phpstan-ignore argument.type
    }

    public function testImageQualityTooHigh(): void
    {
        $this->expectException(InvalidBuilderConfiguration::class);
        $this->expectExceptionMessage('ImageQuality value "101" must be between 1 and 100.');

        $this->getDefaultBuilder()->imageQuality(101); // @phpstan-ignore argument.type
    }
}
