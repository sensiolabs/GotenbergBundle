<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Pdf;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use Psr\Container\ContainerInterface;
use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Pdf\MergePdfBuilder;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Sensiolabs\GotenbergBundle\PayloadResolver\Pdf\MergePdfPayloadResolver;
use Sensiolabs\GotenbergBundle\Tests\Builder\GotenbergBuilderTestCase;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

/**
 * @extends GotenbergBuilderTestCase<MergePdfBuilder>
 */
#[CoversClass(MergePdfBuilder::class)]
#[UsesClass(AssetBaseDirFormatter::class)]
#[UsesClass(MergePdfPayloadResolver::class)]
class MergePdfBuilderTest extends GotenbergBuilderTestCase
{
    protected function createBuilder(GotenbergClientInterface $client, ContainerInterface $dependencies, ContainerInterface $resolvers): BuilderInterface
    {
        $dependencies->set('asset_base_dir_formatter', new AssetBaseDirFormatter(__DIR__, 'fixtures'));
        $this->resolvers->set('.sensiolabs_gotenberg.payload_resolver.merge_pdf_builder', new MergePdfPayloadResolver(self::GOTENBERG_API_VERSION));

        return new MergePdfBuilder($client, $dependencies, $resolvers);
    }

    public function testFiles(): void
    {
        $this->getBuilder()
            ->files('a.pdf', 'b.pdf')
            ->generate()
        ;

        $this->assertGotenbergEndpoint('/forms/pdfengines/merge');
        $this->assertGotenbergFormDataFile('files', 'application/pdf', __DIR__.'/fixtures/a.pdf');
        $this->assertGotenbergFormDataFile('files', 'application/pdf', __DIR__.'/fixtures/b.pdf');
    }

    public function testFilesExtension(): void
    {
        $this->expectException(InvalidOptionsException::class);
        $this->expectExceptionMessage('The option "files" expects files with a "pdf" extension, but "'.__DIR__.'/fixtures/b.png" has a "png" extension.');

        $this->getBuilder()
            ->files('a.pdf', 'b.png')
            ->generate()
        ;
    }
}
