<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Builder\OfficePdfBuilder;
use Symfony\Component\Mime\Part\DataPart;

#[CoversClass(OfficePdfBuilder::class)]
final class OfficePdfBuilderTest extends TestCase
{
    use BuilderTestTrait;

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
        $builder = new OfficePdfBuilder($this->getGotenbergMock(), self::FIXTURE_DIR, $this->getTwig());
        $builder->officeFile($filePath);

        $multipart = $builder->getMultipartFormData();
        $itemOffice = $multipart[array_key_first($multipart)];

        self::assertArrayHasKey('files', $itemOffice);
        self::assertInstanceOf(DataPart::class, $itemOffice['files']);

        $dataPart = $itemOffice['files'];
        self::assertEquals($contentType, $dataPart->getContentType());
    }
}
