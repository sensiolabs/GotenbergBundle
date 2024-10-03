<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Pdf;

use PHPUnit\Framework\Attributes\DataProvider;
use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Pdf\LibreOfficePdfBuilder;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Enumeration\SplitMode;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
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

    public function testWithStringableObject(): void
    {
        $class = new class implements \Stringable {
            public function __toString(): string
            {
                return 'assets/office/document.odt';
            }
        };

        $this->getBuilder()
            ->files($class)
            ->generate()
        ;

        $this->assertGotenbergEndpoint('/forms/libreoffice/convert');
        $this->assertGotenbergFormDataFile('files', 'application/vnd.oasis.opendocument.text', self::FIXTURE_DIR.'/assets/office/document.odt');
    }

    public function testRequiredFileContent(): void
    {
        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('At least one office file is required.');

        $this->getBuilder()
            ->generate()
        ;
    }

    public function testSplitConfigurationRequirement(): void
    {
        $this->expectException(InvalidBuilderConfiguration::class);
        $this->expectExceptionMessage('"splitUnify" can only be at "true" with "pages" mode for "splitMode".');

        $this->getBuilder()
            ->files('assets/office/document.odt')
            ->splitMode(SplitMode::Intervals)
            ->splitUnify()
            ->generate()
        ;
    }
}
