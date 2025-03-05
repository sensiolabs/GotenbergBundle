<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\Chromium;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Enumeration\ScreenshotFormat;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\BehaviorTrait;

/**
 * @template T of BuilderInterface
 */
trait ScreenshotPagePropertiesTestCaseTrait
{
    /** @use BehaviorTrait<T> */
    use BehaviorTrait;

    abstract protected function assertGotenbergFormData(string $field, string $expectedValue): void;

    public function testSetWidthOnScreenshotRendering(): void
    {
        $this->getDefaultBuilder()
            ->width(500)
            ->generate()
        ;

        $this->assertGotenbergFormData('width', '500');
    }

    public function testSetHeightOnScreenshotRendering(): void
    {
        $this->getDefaultBuilder()
            ->height(200)
            ->generate()
        ;

        $this->assertGotenbergFormData('height', '200');
    }

    public function testClipAccordingToTheDeviceDimensions(): void
    {
        $this->getDefaultBuilder()
            ->clip()
            ->generate()
        ;

        $this->assertGotenbergFormData('clip', 'true');
    }

    public function testScreenshotFormatOutput(): void
    {
        $this->getDefaultBuilder()
            ->format(ScreenshotFormat::Png)
            ->generate()
        ;

        $this->assertGotenbergFormData('format', 'png');
    }

    public function testQualityOfTheScreenshotRendering(): void
    {
        $this->getDefaultBuilder()
            ->quality(50)
            ->generate()
        ;

        $this->assertGotenbergFormData('quality', '50');
    }

    public function testSetOmitBackgroundOnRendering(): void
    {
        $this->getDefaultBuilder()
           ->omitBackground(true)
           ->generate()
        ;

        $this->assertGotenbergFormData('omitBackground', 'true');
    }

    public function testOptimizeImageEncodingForSpeed(): void
    {
        $this->getDefaultBuilder()
            ->optimizeForSpeed()
            ->generate()
        ;

        $this->assertGotenbergFormData('optimizeForSpeed', 'true');
    }
}
