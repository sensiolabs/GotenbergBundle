<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Pdf;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use Sensiolabs\GotenbergBundle\Builder\Pdf\AbstractPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\ConvertPdfBuilder;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Sensiolabs\GotenbergBundle\Processor\NullProcessor;
use Sensiolabs\GotenbergBundle\Tests\Builder\AbstractBuilderTestCase;

#[CoversClass(ConvertPdfBuilder::class)]
#[UsesClass(AbstractPdfBuilder::class)]
#[UsesClass(AssetBaseDirFormatter::class)]
final class ConvertPdfBuilderTest extends AbstractBuilderTestCase
{
    private const PDF_DOCUMENTS_DIR = 'assets/pdf';

    public function testEndpointIsCorrect(): void
    {
        $this->gotenbergClient
            ->expects($this->once())
            ->method('call')
            ->with(
                $this->equalTo('/forms/pdfengines/convert'),
                $this->anything(),
                $this->anything(),
            )
        ;

        $this->getConvertPdfBuilder()
            ->files(self::PDF_DOCUMENTS_DIR.'/document.pdf')
            ->pdfUniversalAccess()
            ->build()
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
    }

    /**
     * @param array<mixed> $expected
     */
    #[DataProvider('configurationIsCorrectlySetProvider')]
    public function testConfigurationIsCorrectlySet(string $key, mixed $value, array $expected): void
    {
        $builder = $this->getConvertPdfBuilder();
        $builder->setConfigurations([
            $key => $value,
        ]);
        $builder->files(self::PDF_DOCUMENTS_DIR.'/document.pdf');

        self::assertEquals($expected, $builder->getMultipartFormData()[0]);
    }

    public function testRequiredFormat(): void
    {
        $builder = $this->getConvertPdfBuilder();
        $builder
            ->files(self::PDF_DOCUMENTS_DIR.'/document.pdf')
        ;

        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('At least "pdfa" or "pdfua" must be provided.');

        $builder->getMultipartFormData();
    }

    public function testRequiredPdfFile(): void
    {
        $builder = $this->getConvertPdfBuilder();
        $builder->pdfUniversalAccess();

        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('At least one PDF file is required');

        $builder->getMultipartFormData();
    }

    private function getConvertPdfBuilder(): ConvertPdfBuilder
    {
        return (new ConvertPdfBuilder($this->gotenbergClient, self::$assetBaseDirFormatter))
            ->processor(new NullProcessor())
        ;
    }
}
