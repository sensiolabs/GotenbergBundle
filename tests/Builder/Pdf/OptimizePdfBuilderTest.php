<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Pdf\OptimizePdfBuilder;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Test\Builder\GotenbergBuilderTestCase;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\DownloadFromTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\ImageQualityTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\WebhookTestCaseTrait;
use Symfony\Component\DependencyInjection\Container;

/**
 * @extends GotenbergBuilderTestCase<OptimizePdfBuilder>
 */
final class OptimizePdfBuilderTest extends GotenbergBuilderTestCase
{
    /** @use DownloadFromTestCaseTrait<OptimizePdfBuilder> */
    use DownloadFromTestCaseTrait;

    /** @use ImageQualityTestCaseTrait<OptimizePdfBuilder> */
    use ImageQualityTestCaseTrait;

    /** @use WebhookTestCaseTrait<OptimizePdfBuilder> */
    use WebhookTestCaseTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withGotenbergVersion('8.36.0');
    }

    protected function createBuilder(): OptimizePdfBuilder
    {
        return new OptimizePdfBuilder();
    }

    /**
     * @param OptimizePdfBuilder $builder
     */
    protected function initializeBuilder(BuilderInterface $builder, Container $container): OptimizePdfBuilder
    {
        return $builder
            ->files('pdf/simple_pdf.pdf')
        ;
    }

    public function testEndpointIsCorrect(): void
    {
        $this->getBuilder()
            ->files('pdf/simple_pdf.pdf')
            ->generate()
        ;

        $this->assertGotenbergEndpoint('/forms/pdfengines/optimize');
    }

    public function testAddFilesAsContent(): void
    {
        $this->getBuilder()
            ->files('pdf/simple_pdf.pdf')
            ->generate()
        ;

        $this->assertGotenbergEndpoint('/forms/pdfengines/optimize');
        $this->assertGotenbergFormDataFile('files', 'application/pdf', self::FIXTURE_DIR.'/pdf/simple_pdf.pdf');
    }

    public function testWithStringableObject(): void
    {
        $class = new class implements \Stringable {
            public function __toString(): string
            {
                return 'pdf/simple_pdf.pdf';
            }
        };

        $this->getBuilder()
            ->files($class)
            ->generate()
        ;

        $this->assertGotenbergEndpoint('/forms/pdfengines/optimize');
        $this->assertGotenbergFormDataFile('files', 'application/pdf', self::FIXTURE_DIR.'/pdf/simple_pdf.pdf');
    }

    public function testFilesExtensionRequirement(): void
    {
        $this->expectException(InvalidBuilderConfiguration::class);
        $this->expectExceptionMessage('The file extension "png" is not valid in this context.');

        $this->getBuilder()
            ->files(self::FIXTURE_DIR.'/assets/logo.png')
            ->generate()
        ;
    }

    public function testRequiredFileContent(): void
    {
        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('At least one PDF file is required.');

        $this->getBuilder()
            ->generate()
        ;
    }
}
