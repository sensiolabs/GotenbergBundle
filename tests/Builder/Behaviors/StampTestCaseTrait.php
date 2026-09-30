<?php

declare(strict_types=1);

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors;

use Psr\Log\LoggerInterface;
use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Enumeration\StampSource;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\TextPart;

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

    public function testStampTextImageAndPdf(): void
    {
        $this->withGotenbergVersion('8.36.0');

        $this->getDefaultBuilderWithoutDeprecatedStamp()
            ->addStampText('REMOVED')
            ->stamps([])
            ->addStampText('APPROVED')
            ->addStampImage('assets/logo.png', pages: '2')
            ->addStampText('DRAFT', options: ['opacity' => '0.5'])
            ->addStampPdf('pdf/simple_pdf_1.pdf')
            ->generate()
        ;

        self::assertSame(['text', 'image', 'text', 'pdf'], $this->getStampFormDataValues('stampSource'));
        self::assertSame(['APPROVED', 'logo.png', 'DRAFT', 'simple_pdf_1.pdf'], $this->getStampFormDataValues('stampExpression'));
        self::assertSame(['', '2', '', ''], $this->getStampFormDataValues('stampPages'));
        self::assertSame(['', '', '{"opacity":"0.5"}', ''], $this->getStampFormDataValues('stampOptions'));
        self::assertSame(['logo.png', 'simple_pdf_1.pdf'], $this->getStampFormDataValues('stamp'));
    }

    public function testDeprecatedStampCannotBeCombinedWithTheStamps(): void
    {
        $this->expectException(InvalidBuilderConfiguration::class);
        $this->expectExceptionMessage('The deprecated stamp* methods cannot be combined with addStampText(), addStampImage(), addStampPdf() or stamps().');

        $this->getDefaultBuilderWithoutDeprecatedStamp()
            ->stampPages('1')
            ->addStampText('APPROVED')
            ->generate()
        ;
    }

    public function testStampsFromList(): void
    {
        $this->withGotenbergVersion('8.36.0');

        $this->getDefaultBuilderWithoutDeprecatedStamp()
            ->stamps([
                ['source' => StampSource::Text, 'expression' => 'APPROVED'],
                ['source' => 'pdf', 'file' => 'pdf/simple_pdf_1.pdf', 'pages' => '1'],
            ])
            ->generate()
        ;

        self::assertSame(['text', 'pdf'], $this->getStampFormDataValues('stampSource'));
        self::assertSame(['APPROVED', 'simple_pdf_1.pdf'], $this->getStampFormDataValues('stampExpression'));
        self::assertSame(['', '1'], $this->getStampFormDataValues('stampPages'));
        self::assertSame(['simple_pdf_1.pdf'], $this->getStampFormDataValues('stamp'));
    }

    public function testStampsFromListRequiresAnExpressionForTextEntries(): void
    {
        $this->expectException(InvalidBuilderConfiguration::class);
        $this->expectExceptionMessage('A "text" stamp requires an "expression".');

        $this->getDefaultBuilder()->stamps([['source' => 'text']]);
    }

    public function testStampsFromListRequiresAFileForImageEntries(): void
    {
        $this->expectException(InvalidBuilderConfiguration::class);
        $this->expectExceptionMessage('An "image" or "pdf" stamp requires a "file".');

        $this->getDefaultBuilder()->stamps([['source' => 'image', 'expression' => 'logo.png']]);
    }

    public function testMultipleStampsLogsAWarningBelowGotenberg836(): void
    {
        $this->withGotenbergVersion('8.35.0');
        $messages = $this->collectStampWarnings();

        $this->getDefaultBuilderWithoutDeprecatedStamp()
            ->addStampText('APPROVED')
            ->addStampText('DRAFT')
            ->generate()
        ;

        self::assertContains('The option to apply multiple stamps is not available.', $messages);
    }

    public function testSingleStampDoesNotLogTheMultipleStampsWarning(): void
    {
        $this->withGotenbergVersion('8.35.0');
        $messages = $this->collectStampWarnings();

        $this->getDefaultBuilderWithoutDeprecatedStamp()
            ->addStampText('APPROVED')
            ->generate()
        ;

        self::assertNotContains('The option to apply multiple stamps is not available.', $messages);
    }

    public function testMultipleStampsDoNotLogAWarningOnGotenberg836(): void
    {
        $this->withGotenbergVersion('8.36.0');
        $messages = $this->collectStampWarnings();

        $this->getDefaultBuilderWithoutDeprecatedStamp()
            ->addStampText('APPROVED')
            ->addStampText('DRAFT')
            ->generate()
        ;

        self::assertNotContains('The option to apply multiple stamps is not available.', $messages);
    }

    /**
     * @return T
     */
    private function getDefaultBuilderWithoutDeprecatedStamp(): BuilderInterface
    {
        $builder = $this->getDefaultBuilder();
        foreach (['stampSource', 'stampExpression', 'stampPages', 'stampOptions', 'stamp'] as $key) {
            $builder->getBodyBag()->unset($key);
        }

        return $builder;
    }

    /**
     * @return \ArrayObject<int, mixed>
     */
    private function collectStampWarnings(): \ArrayObject
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
    private function getStampFormDataValues(string $name): array
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
