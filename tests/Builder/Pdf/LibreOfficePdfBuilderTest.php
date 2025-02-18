<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Pdf;

use PHPUnit\Framework\Attributes\DataProvider;
use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Pdf\LibreOfficePdfBuilder;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\LibreOfficeTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\GotenbergBuilderTestCase;
use Symfony\Component\DependencyInjection\Container;

/**
 * @extends GotenbergBuilderTestCase<LibreOfficePdfBuilder>
 */
class LibreOfficePdfBuilderTest extends GotenbergBuilderTestCase
{
    /** @use LibreOfficeTestCaseTrait<LibreOfficePdfBuilder> */
    use LibreOfficeTestCaseTrait;

    protected function createBuilder(GotenbergClientInterface $client, Container $dependencies): LibreOfficePdfBuilder
    {
        return new LibreOfficePdfBuilder($client, $dependencies);
    }

    /**
     * @param LibreOfficePdfBuilder $builder
     */
    protected function initializeBuilder(BuilderInterface $builder, Container $container): LibreOfficePdfBuilder
    {
        return $builder
            ->files('assets/office/document.odt')
        ;
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

    public function testDownloadFrom(): void
    {
        $this->getBuilder()
            ->downloadFrom([
                [
                    'url' => 'http://url/to/file.com',
                    'extraHttpHeaders' => ['MyHeader' => 'MyValue', 'User-Agent' => 'MyValue'],
                ],
            ])
            ->generate()
        ;

        $this->assertGotenbergFormData('downloadFrom', '[{"url":"http:\/\/url\/to\/file.com","extraHttpHeaders":{"MyHeader":"MyValue","User-Agent":"MyValue"}}]');
    }
}
