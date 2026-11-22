<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Pdf\WriteMetadataPdfBuilder;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Test\Builder\GotenbergBuilderTestCase;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\DownloadFromTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\MetadataTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\WebhookTestCaseTrait;
use Symfony\Component\DependencyInjection\Container;

/**
 * @extends GotenbergBuilderTestCase<WriteMetadataPdfBuilder>
 */
final class WriteMetadataPdfBuilderTest extends GotenbergBuilderTestCase
{
    /** @use DownloadFromTestCaseTrait<WriteMetadataPdfBuilder> */
    use DownloadFromTestCaseTrait;

    /** @use MetadataTestCaseTrait<WriteMetadataPdfBuilder> */
    use MetadataTestCaseTrait;

    /** @use WebhookTestCaseTrait<WriteMetadataPdfBuilder> */
    use WebhookTestCaseTrait;

    protected function createBuilder(): WriteMetadataPdfBuilder
    {
        return new WriteMetadataPdfBuilder();
    }

    /**
     * @param WriteMetadataPdfBuilder $builder
     */
    protected function initializeBuilder(BuilderInterface $builder, Container $container): WriteMetadataPdfBuilder
    {
        return $builder
            ->files('pdf/simple_pdf.pdf')
            ->metadata(['Author' => 'SensioLabs'])
        ;
    }

    public function testEndpointIsCorrect(): void
    {
        $this->getBuilder()
            ->files('pdf/simple_pdf.pdf')
            ->metadata(['Author' => 'SensioLabs'])
            ->generate()
        ;

        $this->assertGotenbergEndpoint('/forms/pdfengines/metadata/write');
        $this->assertGotenbergFormDataFile('files', 'application/pdf', self::FIXTURE_DIR.'/pdf/simple_pdf.pdf');
        $this->assertGotenbergFormData('metadata', '{"Author":"SensioLabs"}');
    }

    public function testFilesExtensionRequirement(): void
    {
        $this->expectException(InvalidBuilderConfiguration::class);
        $this->expectExceptionMessage('The file extension "png" is not valid in this context.');

        $this->getBuilder()
            ->files(self::FIXTURE_DIR.'/assets/logo.png')
            ->metadata(['Author' => 'SensioLabs'])
            ->generate()
        ;
    }

    public function testRequiredFileContent(): void
    {
        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('At least one PDF file is required.');

        $this->getBuilder()
            ->metadata(['Author' => 'SensioLabs'])
            ->generate()
        ;
    }

    public function testRequiredMetadata(): void
    {
        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('Field "metadata" must be provided.');

        $this->getBuilder()
            ->files('pdf/simple_pdf.pdf')
            ->generate()
        ;
    }
}
