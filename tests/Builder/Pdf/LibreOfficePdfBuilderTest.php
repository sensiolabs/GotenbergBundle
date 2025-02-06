<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Pdf;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use Psr\Container\ContainerInterface;
use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Pdf\LibreOfficePdfBuilder;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Sensiolabs\GotenbergBundle\Tests\Builder\GotenbergBuilderTestCase;

/**
 * @extends GotenbergBuilderTestCase<LibreOfficePdfBuilder>
 */
#[CoversClass(LibreOfficePdfBuilder::class)]
#[UsesClass(AssetBaseDirFormatter::class)]
class LibreOfficePdfBuilderTest extends GotenbergBuilderTestCase
{
    protected function createBuilder(GotenbergClientInterface $client, ContainerInterface $dependencies): BuilderInterface
    {
        $dependencies->set('asset_base_dir_formatter', new AssetBaseDirFormatter(self::FIXTURE_DIR, self::FIXTURE_DIR));

        return new LibreOfficePdfBuilder($client, $dependencies);
    }

    public static function provideValidOfficeFiles(): \Generator
    {
        yield 'odt' => ['assets/office/document.odt', 'application/vnd.oasis.opendocument.text'];
        yield 'docx' => ['assets/office/document_1.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        yield 'html' => ['assets/office/document_2.html', 'text/html'];
        yield 'xslx' => ['assets/office/document_3.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
        yield 'pptx' => ['assets/office/document_4.pptx', 'application/vnd.openxmlformats-officedocument.presentationml.presentation'];
    }

    #[DataProvider('provideValidOfficeFiles')]
    public function testOfficeFiles(string $filePath, string $contentType): void
    {
        $this->getBuilder()
            ->files($filePath)
            ->generate()
        ;

        $this->assertGotenbergEndpoint('/forms/libreoffice/convert');
        $this->assertGotenbergFormDataFile('files', $contentType, self::FIXTURE_DIR.'/'.$filePath);
    }

    public function testRequiredFile(): void
    {
        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('At least one office file is required.');

        $this->getBuilder()
            ->generate()
        ;
    }
}
