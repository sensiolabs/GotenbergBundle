<?php

declare(strict_types=1);

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Enumeration\StampSource;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;

/**
 * @template T of BuilderInterface
 */
trait StampTestCaseTrait
{
    /** @use BehaviorTrait<T> */
    use BehaviorTrait;

    abstract protected function assertGotenbergFormData(string $field, string $expectedValue): void;

    public function testStampSourceText(): void
    {
        $this->withGotenbergVersion('8.28.0');

        $this->getDefaultBuilder()
            ->stampSource(StampSource::Text)
            ->generate()
        ;

        $this->assertGotenbergFormData('stampSource', StampSource::Text->value);
    }

    public function testStampExpression(): void
    {
        $this->withGotenbergVersion('8.28.0');

        $this->getDefaultBuilder()
            ->stampExpression('APPROVED')
            ->generate()
        ;

        $this->assertGotenbergFormData('stampExpression', 'APPROVED');
    }

    public function testStampPages(): void
    {
        $this->withGotenbergVersion('8.28.0');

        $this->getDefaultBuilder()
            ->stampPages('1-3')
            ->generate()
        ;

        $this->assertGotenbergFormData('stampPages', '1-3');
    }

    public function testStampOptions(): void
    {
        $this->withGotenbergVersion('8.28.0');

        $this->getDefaultBuilder()
            ->stampOptions(['opacity' => '0.5'])
            ->generate()
        ;

        $this->assertGotenbergFormData('stampOptions', '{"opacity":"0.5"}');
    }

    public function testStampFile(): void
    {
        $this->withGotenbergVersion('8.28.0');
        $this->container->set('asset_base_dir_formatter', new AssetBaseDirFormatter(self::FIXTURE_DIR, [self::FIXTURE_DIR]));

        $this->getDefaultBuilder()
            ->stampFile('pdf/simple_pdf.pdf')
            ->generate()
        ;

        $this->assertGotenbergFormDataFile('stamp', 'application/pdf', self::FIXTURE_DIR.'/pdf/simple_pdf.pdf');
    }
}
