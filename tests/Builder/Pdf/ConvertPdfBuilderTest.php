<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Pdf;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Pdf\ConvertPdfBuilder;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Sensiolabs\GotenbergBundle\Tests\Builder\GotenbergBuilderTestCase;
use Symfony\Component\DependencyInjection\Container;

/**
 * @extends GotenbergBuilderTestCase<ConvertPdfBuilder>
 */
#[CoversClass(ConvertPdfBuilder::class)]
#[UsesClass(AssetBaseDirFormatter::class)]
final class ConvertPdfBuilderTest extends GotenbergBuilderTestCase
{
    protected function createBuilder(GotenbergClientInterface $client, Container $dependencies): BuilderInterface
    {
        return new ConvertPdfBuilder($client, $dependencies);
    }

    public function testFiles(): void
    {
        $this->getBuilder()
            ->files('pdf/simple_pdf.pdf')
            ->pdfUniversalAccess()
            ->generate()
        ;

        $this->assertGotenbergEndpoint('/forms/pdfengines/convert');
        $this->assertGotenbergFormDataFile('files', 'application/pdf', self::FIXTURE_DIR.'/pdf/simple_pdf.pdf');
    }

    public function testRequiredConfiguration(): void
    {
        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('At least "pdfa" or "pdfua" must be provided.');

        $this->getBuilder()
            ->generate()
        ;
    }

    public function testRequiredFile(): void
    {
        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('At least one PDF file is required.');

        $this->getBuilder()
            ->pdfUniversalAccess()
            ->generate()
        ;
    }

    public function testFilesExtension(): void
    {
        $this->expectException(InvalidBuilderConfiguration::class);
        $this->expectExceptionMessage('The file extension "png" is not valid in this context.');

        $this->getBuilder()
            ->files('b.png')
            ->generate()
        ;
    }
}
