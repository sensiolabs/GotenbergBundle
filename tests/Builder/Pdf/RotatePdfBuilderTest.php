<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Pdf\RotatePdfBuilder;
use Sensiolabs\GotenbergBundle\Enumeration\RotateAngle;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Test\Builder\GotenbergBuilderTestCase;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\DownloadFromTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\RotateTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\WebhookTestCaseTrait;
use Symfony\Component\DependencyInjection\Container;

/**
 * @extends GotenbergBuilderTestCase<RotatePdfBuilder>
 */
final class RotatePdfBuilderTest extends GotenbergBuilderTestCase
{
    /** @use DownloadFromTestCaseTrait<RotatePdfBuilder> */
    use DownloadFromTestCaseTrait;

    /** @use RotateTestCaseTrait<RotatePdfBuilder> */
    use RotateTestCaseTrait;

    /** @use WebhookTestCaseTrait<RotatePdfBuilder> */
    use WebhookTestCaseTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withGotenbergVersion('8.28.0');
    }

    protected function createBuilder(): RotatePdfBuilder
    {
        return new RotatePdfBuilder();
    }

    /**
     * @param RotatePdfBuilder $builder
     */
    protected function initializeBuilder(BuilderInterface $builder, Container $container): RotatePdfBuilder
    {
        return $builder
            ->files('pdf/simple_pdf.pdf')
            ->rotateAngle(RotateAngle::Rotate90)
        ;
    }

    public function testEndpointIsCorrect(): void
    {
        $this->getBuilder()
            ->files('pdf/simple_pdf.pdf')
            ->rotateAngle(RotateAngle::Rotate90)
            ->generate()
        ;

        $this->assertGotenbergEndpoint('/forms/pdfengines/rotate');
    }

    public function testAddFilesAsContent(): void
    {
        $this->getBuilder()
            ->files('pdf/simple_pdf.pdf')
            ->rotateAngle(RotateAngle::Rotate90)
            ->generate()
        ;

        $this->assertGotenbergEndpoint('/forms/pdfengines/rotate');
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
            ->rotateAngle(RotateAngle::Rotate90)
            ->generate()
        ;

        $this->assertGotenbergEndpoint('/forms/pdfengines/rotate');
        $this->assertGotenbergFormDataFile('files', 'application/pdf', self::FIXTURE_DIR.'/pdf/simple_pdf.pdf');
    }

    public function testFilesExtensionRequirement(): void
    {
        $this->expectException(InvalidBuilderConfiguration::class);
        $this->expectExceptionMessage('The file extension "png" is not valid in this context.');

        $this->getBuilder()
            ->files(self::FIXTURE_DIR.'/assets/logo.png')
            ->rotateAngle(RotateAngle::Rotate90)
            ->generate()
        ;
    }

    public function testRequiredFileContent(): void
    {
        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('At least one PDF file is required.');

        $this->getBuilder()
            ->rotateAngle(RotateAngle::Rotate90)
            ->generate()
        ;
    }

    public function testRequiredRotateAngleField(): void
    {
        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('Field "rotateAngle" must be provided.');

        $this->getBuilder()
            ->files('pdf/simple_pdf.pdf')
            ->generate()
        ;
    }
}
