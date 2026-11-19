<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Pdf;

use PHPUnit\Framework\Attributes\DataProvider;
use Psr\Log\LoggerInterface;
use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Pdf\LibreOfficePdfBuilder;
use Sensiolabs\GotenbergBundle\Enumeration\SplitMode;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Test\Builder\GotenbergBuilderTestCase;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\LibreOfficeTestCaseTrait;
use Symfony\Component\DependencyInjection\Container;

/**
 * @extends GotenbergBuilderTestCase<LibreOfficePdfBuilder>
 */
class LibreOfficePdfBuilderTest extends GotenbergBuilderTestCase
{
    /** @use LibreOfficeTestCaseTrait<LibreOfficePdfBuilder> */
    use LibreOfficeTestCaseTrait;

    protected function createBuilder(): LibreOfficePdfBuilder
    {
        return new LibreOfficePdfBuilder();
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
        yield 'ppsx' => ['assets/office/document_5.ppsx', 'application/vnd.openxmlformats-officedocument.presentationml.slideshow'];
        yield 'ppsm' => ['assets/office/document_6.ppsm', 'application/vnd.ms-powerpoint.slideshow.macroenabled.12'];
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

    public static function provideSlideshowFiles(): \Generator
    {
        yield 'ppsx' => ['assets/office/document_5.ppsx', 'ppsx'];
        yield 'ppsm' => ['assets/office/document_6.ppsm', 'ppsm'];
    }

    #[DataProvider('provideSlideshowFiles')]
    public function testSlideshowFilesLogAWarningBeforeGotenberg836(string $filePath, string $extension): void
    {
        $this->withGotenbergVersion('8.35.0');

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())
            ->method('warning')
            ->with('Gotenberg {$operator} {$version} required: {$message}', [
                'operator' => '>=',
                'version' => '8.36',
                'message' => "The \"{$extension}\" extension is not available.",
            ])
        ;
        $this->container->set('logger', $logger);

        $this->getBuilder()
            ->files($filePath)
            ->generate()
        ;
    }

    #[DataProvider('provideSlideshowFiles')]
    public function testSlideshowFilesDoNotLogAWarningSinceGotenberg836(string $filePath): void
    {
        $this->withGotenbergVersion('8.36.0');

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->never())->method('warning');
        $this->container->set('logger', $logger);

        $this->getBuilder()
            ->files($filePath)
            ->generate()
        ;
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

    public function testZoomConfigurationRequirement(): void
    {
        $this->expectException(InvalidBuilderConfiguration::class);
        $this->expectExceptionMessage('"zoom" can only be set when "magnification" is set to "Magnification::UseZoomValue (4)".');

        $this->getBuilder()
            ->files('assets/office/document.odt')
            ->zoom(50)
            ->generate()
        ;
    }
}
