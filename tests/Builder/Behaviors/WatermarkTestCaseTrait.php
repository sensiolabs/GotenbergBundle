<?php

declare(strict_types=1);

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Enumeration\WatermarkSource;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;

/**
 * @template T of BuilderInterface
 */
trait WatermarkTestCaseTrait
{
    /** @use BehaviorTrait<T> */
    use BehaviorTrait;

    abstract protected function assertGotenbergFormData(string $field, string $expectedValue): void;

    public function testWatermarkSourceText(): void
    {
        $this->withGotenbergVersion('8.28.0');

        $this->getDefaultBuilder()
            ->watermarkSource(WatermarkSource::Text)
            ->generate()
        ;

        $this->assertGotenbergFormData('watermarkSource', WatermarkSource::Text->value);
    }

    public function testWatermarkExpression(): void
    {
        $this->withGotenbergVersion('8.28.0');

        $this->getDefaultBuilder()
            ->watermarkExpression('CONFIDENTIAL')
            ->generate()
        ;

        $this->assertGotenbergFormData('watermarkExpression', 'CONFIDENTIAL');
    }

    public function testWatermarkPages(): void
    {
        $this->withGotenbergVersion('8.28.0');

        $this->getDefaultBuilder()
            ->watermarkPages('1-3')
            ->generate()
        ;

        $this->assertGotenbergFormData('watermarkPages', '1-3');
    }

    public function testWatermarkOptions(): void
    {
        $this->withGotenbergVersion('8.28.0');

        $this->getDefaultBuilder()
            ->watermarkOptions(['opacity' => '0.5'])
            ->generate()
        ;

        $this->assertGotenbergFormData('watermarkOptions', '{"opacity":"0.5"}');
    }

    public function testWatermarkFile(): void
    {
        $this->withGotenbergVersion('8.28.0');
        $this->container->set('asset_base_dir_formatter', new AssetBaseDirFormatter(self::FIXTURE_DIR, [self::FIXTURE_DIR]));

        $this->getDefaultBuilder()
            ->watermarkFile('pdf/simple_pdf.pdf')
            ->generate()
        ;

        $this->assertGotenbergFormDataFile('watermark', 'application/pdf', self::FIXTURE_DIR.'/pdf/simple_pdf.pdf');
    }
}
