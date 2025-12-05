<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Pdf\EmbedPdfBuilder;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Test\Builder\GotenbergBuilderTestCase;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\DownloadFromTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\EmbedTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\WebhookTestCaseTrait;
use Symfony\Component\DependencyInjection\Container;

/**
 * @extends GotenbergBuilderTestCase<EmbedPdfBuilder>
 */
final class EmbedPdfBuilderTest extends GotenbergBuilderTestCase
{
    /** @use DownloadFromTestCaseTrait<EmbedPdfBuilder> */
    use DownloadFromTestCaseTrait;

    /** @use EmbedTestCaseTrait<EmbedPdfBuilder> */
    use EmbedTestCaseTrait;

    /** @use WebhookTestCaseTrait<EmbedPdfBuilder> */
    use WebhookTestCaseTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withGotenbergVersion('8.25.0');
    }

    protected function createBuilder(): EmbedPdfBuilder
    {
        return new EmbedPdfBuilder();
    }

    /**
     * @param EmbedPdfBuilder $builder
     */
    protected function initializeBuilder(BuilderInterface $builder, Container $container): EmbedPdfBuilder
    {
        return $builder
            ->files('pdf/simple_pdf.pdf')
            ->embeds('embed/facturX.xml')
        ;
    }

    public function testAddFilesAndEmbedAsContent(): void
    {
        $this->getDefaultBuilder()
            ->generate()
        ;

        $this->assertGotenbergEndpoint('/forms/pdfengines/embed');
        $this->assertGotenbergFormDataFile('embeds', 'application/xml', self::FIXTURE_DIR.'/embed/facturX.xml');
    }

    public function testRequiredFileContent(): void
    {
        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('At least one PDF file is required.');

        $this->getBuilder()
            ->generate()
        ;
    }

    public function testRequiredEmbedFile(): void
    {
        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('At least one embed file is required.');

        $this->getBuilder()
            ->files('pdf/simple_pdf.pdf')
            ->generate()
        ;
    }

    public function testWithStringableObjects(): void
    {
        $classEmbed = new class implements \Stringable {
            public function __toString(): string
            {
                return 'embed/facturX.xml';
            }
        };

        $this->getBuilder()
            ->files('pdf/simple_pdf.pdf')
            ->embeds($classEmbed)
            ->generate()
        ;

        $this->assertGotenbergEndpoint('/forms/pdfengines/embed');
        $this->assertGotenbergFormDataFile('files', 'application/pdf', self::FIXTURE_DIR.'/pdf/simple_pdf.pdf');
        $this->assertGotenbergFormDataFile('embeds', 'application/xml', self::FIXTURE_DIR.'/embed/facturX.xml');
    }
}
