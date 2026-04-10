<?php

declare(strict_types=1);

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Enumeration\RotateAngle;

/**
 * @template T of BuilderInterface
 */
trait RotateTestCaseTrait
{
    /** @use BehaviorTrait<T> */
    use BehaviorTrait;

    abstract protected function assertGotenbergFormData(string $field, string $expectedValue): void;

    public function testRotateAngle90(): void
    {
        $this->getDefaultBuilder()
            ->rotateAngle(RotateAngle::Rotate90)
            ->generate()
        ;

        $this->assertGotenbergFormData('rotateAngle', '90');
    }

    public function testRotateAngle180(): void
    {
        $this->getDefaultBuilder()
            ->rotateAngle(RotateAngle::Rotate180)
            ->generate()
        ;

        $this->assertGotenbergFormData('rotateAngle', '180');
    }

    public function testRotateAngle270(): void
    {
        $this->getDefaultBuilder()
            ->rotateAngle(RotateAngle::Rotate270)
            ->generate()
        ;

        $this->assertGotenbergFormData('rotateAngle', '270');
    }

    public function testRotatePages(): void
    {
        $this->getDefaultBuilder()
            ->rotatePages('1-2')
            ->generate()
        ;

        $this->assertGotenbergFormData('rotatePages', '1-2');
    }

    public function testUnsetRotateAngle(): void
    {
        $builder = $this->getDefaultBuilder()
            ->rotateAngle(RotateAngle::Rotate90)
        ;

        self::assertArrayHasKey('rotateAngle', $builder->getBodyBag()->all());

        $builder->rotateAngle(null);
        self::assertArrayNotHasKey('rotateAngle', $builder->getBodyBag()->all());
    }

    public function testUnsetRotatePages(): void
    {
        $builder = $this->getDefaultBuilder()
            ->rotatePages('1-2')
        ;

        self::assertArrayHasKey('rotatePages', $builder->getBodyBag()->all());

        $builder->rotatePages(null);
        self::assertArrayNotHasKey('rotatePages', $builder->getBodyBag()->all());
    }
}
