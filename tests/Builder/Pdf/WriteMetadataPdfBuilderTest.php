<?php

namespace Builder\Pdf;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use Sensiolabs\GotenbergBundle\Builder\Pdf\AbstractPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\WriteMetadataPdfBuilder;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Sensiolabs\GotenbergBundle\Tests\Builder\AbstractBuilderTestCase;

#[CoversClass(WriteMetadataPdfBuilder::class)]
#[UsesClass(AbstractPdfBuilder::class)]
#[UsesClass(AssetBaseDirFormatter::class)]
final class WriteMetadataPdfBuilderTest extends AbstractBuilderTestCase
{
    private const PDF_DOCUMENTS_DIR = 'pdf';

    public function testEndpointIsCorrect(): void
    {
        $this->gotenbergClient
            ->expects($this->once())
            ->method('call')
            ->with(
                $this->equalTo('/forms/pdfengines/metadata/write'),
                $this->anything(),
                $this->anything(),
            )
        ;

        $this->getWriteMetadataPdfBuilder()
            ->files(
                self::PDF_DOCUMENTS_DIR.'/simple_pdf.pdf',
                self::PDF_DOCUMENTS_DIR.'/simple_pdf_1.pdf',
            )
            ->generate()
        ;
    }

    public static function configurationIsCorrectlySetProvider(): \Generator
    {
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
        $builder = $this->getWriteMetadataPdfBuilder();
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
        $builder = $this->getWriteMetadataPdfBuilder();

        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('At least one PDF file is required');

        $builder->getMultipartFormData();
    }

    private function getWriteMetadataPdfBuilder(): WriteMetadataPdfBuilder
    {
        return new WriteMetadataPdfBuilder($this->gotenbergClient, self::$assetBaseDirFormatter);
    }
}
