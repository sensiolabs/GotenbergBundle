<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Pdf;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use Sensiolabs\GotenbergBundle\Builder\GotenbergFileResult;
use Sensiolabs\GotenbergBundle\Builder\Pdf\AbstractPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\MergePdfBuilder;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Sensiolabs\GotenbergBundle\Processor\NullProcessor;
use Sensiolabs\GotenbergBundle\Tests\Builder\AbstractBuilderTestCase;
use Symfony\Component\Mime\Part\DataPart;

#[CoversClass(MergePdfBuilder::class)]
#[UsesClass(AbstractPdfBuilder::class)]
#[UsesClass(AssetBaseDirFormatter::class)]
#[UsesClass(GotenbergFileResult::class)]
final class MergePdfBuilderTest extends AbstractBuilderTestCase
{
    public const PDF_DOCUMENTS_DIR = 'pdf';

    public function testEndpointIsCorrect(): void
    {
        $this->gotenbergClient
            ->expects($this->once())
            ->method('call')
            ->with(
                $this->equalTo('/forms/pdfengines/merge'),
                $this->anything(),
                $this->anything(),
            )
        ;

        $this->getMergePdfBuilder()
            ->files(
                self::PDF_DOCUMENTS_DIR.'/simple_pdf.pdf',
                self::PDF_DOCUMENTS_DIR.'/simple_pdf_1.pdf',
            )
            ->generate()
        ;
    }

    public static function configurationIsCorrectlySetProvider(): \Generator
    {
        yield 'pdf_format' => ['pdf_format', 'PDF/A-1b', [
            'pdfa' => 'PDF/A-1b',
        ]];
        yield 'pdf_universal_access' => ['pdf_universal_access', false, [
            'pdfua' => 'false',
        ]];
        yield 'metadata' => ['metadata', ['Author' => 'SensioLabs'], [
            'metadata' => '{"Author":"SensioLabs"}',
        ]];
    }

    /**
     * @param array<mixed> $expected
     */
    #[DataProvider('configurationIsCorrectlySetProvider')]
    public function testConfigurationIsCorrectlySet(string $key, mixed $value, array $expected): void
    {
        $builder = $this->getMergePdfBuilder();
        $builder->setConfigurations([
            $key => $value,
        ]);
        $builder->files(
            self::PDF_DOCUMENTS_DIR.'/simple_pdf.pdf',
            self::PDF_DOCUMENTS_DIR.'/simple_pdf_1.pdf',
        );

        self::assertEquals($expected, $builder->getMultipartFormData()[0]);
    }

    public function testRequiredFormData(): void
    {
        $builder = $this->getMergePdfBuilder();

        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('At least one PDF file is required');

        $builder->getMultipartFormData();
    }

    #[DataProvider('supportedFilePathsProvider')]
    public function testSupportedFormat(mixed $supportedFilePath): void
    {
        $builder = $this->getMergePdfBuilder();
        $builder
            ->files($supportedFilePath)
        ;

        $data = $builder->getMultipartFormData();

        /* @var DataPart $dataPart */
        self::assertInstanceOf(DataPart::class, $dataPart = $data[0]['files']);
        self::assertSame(basename((string) $supportedFilePath), $dataPart->getFilename());
    }

    /**
     * @return array<list<string|\Stringable>>
     */
    public static function supportedFilePathsProvider(): array
    {
        return [
            [self::PDF_DOCUMENTS_DIR.'/simple_pdf.pdf'],
            [new class implements \Stringable {
                public function __toString(): string
                {
                    return MergePdfBuilderTest::PDF_DOCUMENTS_DIR.'/simple_pdf.pdf';
                }
            }],
            [new \SplFileInfo(self::PDF_DOCUMENTS_DIR.'/simple_pdf.pdf')],
        ];
    }

    private function getMergePdfBuilder(): MergePdfBuilder
    {
        return (new MergePdfBuilder($this->gotenbergClient, self::$assetBaseDirFormatter, $this->webhookConfigurationRegistry))
            ->processor(new NullProcessor())
        ;
    }
}
