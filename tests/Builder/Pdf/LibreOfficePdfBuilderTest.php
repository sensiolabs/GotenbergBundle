<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Pdf;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use Sensiolabs\GotenbergBundle\Builder\Pdf\AbstractPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\LibreOfficePdfBuilder;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Sensiolabs\GotenbergBundle\Tests\Builder\AbstractBuilderTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Mime\Part\DataPart;

#[CoversClass(LibreOfficePdfBuilder::class)]
#[UsesClass(AbstractPdfBuilder::class)]
#[UsesClass(AssetBaseDirFormatter::class)]
#[UsesClass(Filesystem::class)]
final class LibreOfficePdfBuilderTest extends AbstractBuilderTestCase
{
    private const OFFICE_DOCUMENTS_DIR = 'assets/office';

    /**
     * @return array<string, list<string>>
     */
    public static function provideValidOfficeFiles(): iterable
    {
        yield 'odt' => [self::OFFICE_DOCUMENTS_DIR.'/document.odt', 'application/vnd.oasis.opendocument.text'];
        yield 'docx' => [self::OFFICE_DOCUMENTS_DIR.'/document_1.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        yield 'html' => [self::OFFICE_DOCUMENTS_DIR.'/document_2.html', 'text/html'];
        yield 'xslx' => [self::OFFICE_DOCUMENTS_DIR.'/document_3.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
        yield 'pptx' => [self::OFFICE_DOCUMENTS_DIR.'/document_4.pptx', 'application/vnd.openxmlformats-officedocument.presentationml.presentation'];
    }

    #[DataProvider('provideValidOfficeFiles')]
    public function testOfficeFiles(string $filePath, string $contentType): void
    {
        $client = $this->createMock(GotenbergClientInterface::class);
        $assetBaseDirFormatter = new AssetBaseDirFormatter(new Filesystem(), self::FIXTURE_DIR, self::FIXTURE_DIR);

        $builder = new LibreOfficePdfBuilder($client, $assetBaseDirFormatter);
        $builder->files($filePath);

        $multipartFormData = $builder->getMultipartFormData();

        self::assertCount(1, $multipartFormData);

        self::assertArrayHasKey(0, $multipartFormData);
        self::assertIsArray($multipartFormData[0]);
        self::assertArrayHasKey('files', $multipartFormData[0]);
        self::assertInstanceOf(DataPart::class, $multipartFormData[0]['files']);
        self::assertSame($contentType, $multipartFormData[0]['files']->getContentType());
    }
}
