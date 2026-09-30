<?php

declare(strict_types=1);

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors;

use Psr\Log\LoggerInterface;
use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Enumeration\WatermarkSource;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\TextPart;

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

    public function testWatermarkTextImageAndPdf(): void
    {
        $this->withGotenbergVersion('8.36.0');

        $this->getDefaultBuilderWithoutDeprecatedWatermark()
            ->addWatermarkText('REMOVED')
            ->watermarks([])
            ->addWatermarkText('CONFIDENTIAL')
            ->addWatermarkImage('assets/logo.png', pages: '2')
            ->addWatermarkText('DRAFT', options: ['opacity' => '0.5'])
            ->addWatermarkPdf('pdf/simple_pdf_1.pdf')
            ->generate()
        ;

        self::assertSame(['text', 'image', 'text', 'pdf'], $this->getWatermarkFormDataValues('watermarkSource'));
        self::assertSame(['CONFIDENTIAL', 'logo.png', 'DRAFT', 'simple_pdf_1.pdf'], $this->getWatermarkFormDataValues('watermarkExpression'));
        self::assertSame(['', '2', '', ''], $this->getWatermarkFormDataValues('watermarkPages'));
        self::assertSame(['', '', '{"opacity":"0.5"}', ''], $this->getWatermarkFormDataValues('watermarkOptions'));
        self::assertSame(['logo.png', 'simple_pdf_1.pdf'], $this->getWatermarkFormDataValues('watermark'));
    }

    public function testDeprecatedWatermarkCannotBeCombinedWithTheWatermarks(): void
    {
        $this->expectException(InvalidBuilderConfiguration::class);
        $this->expectExceptionMessage('The deprecated watermark* methods cannot be combined with addWatermarkText(), addWatermarkImage(), addWatermarkPdf() or watermarks().');

        $this->getDefaultBuilderWithoutDeprecatedWatermark()
            ->watermarkPages('1')
            ->addWatermarkText('CONFIDENTIAL')
            ->generate()
        ;
    }

    public function testWatermarksFromList(): void
    {
        $this->withGotenbergVersion('8.36.0');

        $this->getDefaultBuilderWithoutDeprecatedWatermark()
            ->watermarks([
                ['source' => WatermarkSource::Text, 'expression' => 'CONFIDENTIAL'],
                ['source' => 'pdf', 'file' => 'pdf/simple_pdf_1.pdf', 'pages' => '1'],
            ])
            ->generate()
        ;

        self::assertSame(['text', 'pdf'], $this->getWatermarkFormDataValues('watermarkSource'));
        self::assertSame(['CONFIDENTIAL', 'simple_pdf_1.pdf'], $this->getWatermarkFormDataValues('watermarkExpression'));
        self::assertSame(['', '1'], $this->getWatermarkFormDataValues('watermarkPages'));
        self::assertSame(['simple_pdf_1.pdf'], $this->getWatermarkFormDataValues('watermark'));
    }

    public function testWatermarksFromListRequiresAnExpressionForTextEntries(): void
    {
        $this->expectException(InvalidBuilderConfiguration::class);
        $this->expectExceptionMessage('A "text" watermark requires an "expression".');

        $this->getDefaultBuilder()->watermarks([['source' => 'text']]);
    }

    public function testWatermarksFromListRequiresAFileForImageEntries(): void
    {
        $this->expectException(InvalidBuilderConfiguration::class);
        $this->expectExceptionMessage('An "image" or "pdf" watermark requires a "file".');

        $this->getDefaultBuilder()->watermarks([['source' => 'image', 'expression' => 'logo.png']]);
    }

    public function testMultipleWatermarksLogsAWarningBelowGotenberg836(): void
    {
        $this->withGotenbergVersion('8.35.0');
        $messages = $this->collectWatermarkWarnings();

        $this->getDefaultBuilderWithoutDeprecatedWatermark()
            ->addWatermarkText('CONFIDENTIAL')
            ->addWatermarkText('DRAFT')
            ->generate()
        ;

        self::assertContains('The option to apply multiple watermarks is not available.', $messages);
    }

    public function testSingleWatermarkDoesNotLogTheMultipleWatermarksWarning(): void
    {
        $this->withGotenbergVersion('8.35.0');
        $messages = $this->collectWatermarkWarnings();

        $this->getDefaultBuilderWithoutDeprecatedWatermark()
            ->addWatermarkText('CONFIDENTIAL')
            ->generate()
        ;

        self::assertNotContains('The option to apply multiple watermarks is not available.', $messages);
    }

    public function testMultipleWatermarksDoNotLogAWarningOnGotenberg836(): void
    {
        $this->withGotenbergVersion('8.36.0');
        $messages = $this->collectWatermarkWarnings();

        $this->getDefaultBuilderWithoutDeprecatedWatermark()
            ->addWatermarkText('CONFIDENTIAL')
            ->addWatermarkText('DRAFT')
            ->generate()
        ;

        self::assertNotContains('The option to apply multiple watermarks is not available.', $messages);
    }

    /**
     * @return T
     */
    private function getDefaultBuilderWithoutDeprecatedWatermark(): BuilderInterface
    {
        $builder = $this->getDefaultBuilder();
        foreach (['watermarkSource', 'watermarkExpression', 'watermarkPages', 'watermarkOptions', 'watermark'] as $key) {
            $builder->getBodyBag()->unset($key);
        }

        return $builder;
    }

    /**
     * @return \ArrayObject<int, mixed>
     */
    private function collectWatermarkWarnings(): \ArrayObject
    {
        /** @var \ArrayObject<int, mixed> $messages */
        $messages = new \ArrayObject();
        $logger = $this->createMock(LoggerInterface::class);
        $logger->method('warning')->willReturnCallback(static function (string $message, array $context) use ($messages): void {
            $messages[] = $context['message'];
        });
        $this->container->set('logger', $logger);

        return $messages;
    }

    /**
     * @return list<string>
     */
    private function getWatermarkFormDataValues(string $name): array
    {
        $values = [];
        /** @var TextPart|DataPart $part */
        foreach ($this->client->getBody() as $part) {
            if ($part->getName() === $name) {
                $values[] = $part instanceof DataPart && null !== $part->getFilename() ? $part->getFilename() : $part->getBody();
            }
        }

        return $values;
    }
}
