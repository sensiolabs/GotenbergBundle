<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Pdf;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Pdf\MergePdfBuilder;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\MetadataTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\PdfFormatTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\WebhookTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\GotenbergBuilderTestCase;
use Symfony\Component\DependencyInjection\Container;

/**
 * @extends GotenbergBuilderTestCase<MergePdfBuilder>
 */
#[CoversClass(MergePdfBuilder::class)]
#[UsesClass(AssetBaseDirFormatter::class)]
final class MergePdfBuilderTest extends GotenbergBuilderTestCase
{
    /** @use MetadataTestCaseTrait<MergePdfBuilder> */
    use MetadataTestCaseTrait;

    /** @use PdfFormatTestCaseTrait<MergePdfBuilder> */
    use PdfFormatTestCaseTrait;

    /** @use WebhookTestCaseTrait<MergePdfBuilder> */
    use WebhookTestCaseTrait;

    protected function createBuilder(GotenbergClientInterface $client, Container $dependencies): MergePdfBuilder
    {
        return new MergePdfBuilder($client, $dependencies);
    }

    /**
     * @param MergePdfBuilder $builder
     */
    protected function initializeBuilder(BuilderInterface $builder, Container $container): MergePdfBuilder
    {
        return $builder
            ->files('pdf/simple_pdf.pdf', 'pdf/simple_pdf_1.pdf')
        ;
    }

    public function testFiles(): void
    {
        $this->getBuilder()
            ->files('pdf/simple_pdf.pdf', 'pdf/simple_pdf_1.pdf')
            ->generate()
        ;

        $this->assertGotenbergEndpoint('/forms/pdfengines/merge');
        $this->assertGotenbergFormDataFile('files', 'application/pdf', self::FIXTURE_DIR.'/pdf/simple_pdf.pdf');
        $this->assertGotenbergFormDataFile('files', 'application/pdf', self::FIXTURE_DIR.'/pdf/simple_pdf_1.pdf');
    }

    public function testFilesExtension(): void
    {
        $this->expectException(InvalidBuilderConfiguration::class);
        $this->expectExceptionMessage('The file extension "png" is not valid in this context.');

        $this->getBuilder()
            ->files('simple_pdf.pdf', 'b.png')
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
                [
                    'url' => 'http://url/to/second/file.com',
                    'extraHttpHeaders' => ['User-Agent' => 'MyValue'],
                ],
            ])
            ->generate()
        ;

        $this->assertGotenbergFormData('downloadFrom', '[{"url":"http:\/\/url\/to\/file.com","extraHttpHeaders":{"MyHeader":"MyValue","User-Agent":"MyValue"}},{"url":"http:\/\/url\/to\/second\/file.com","extraHttpHeaders":{"User-Agent":"MyValue"}}]');
    }
}
