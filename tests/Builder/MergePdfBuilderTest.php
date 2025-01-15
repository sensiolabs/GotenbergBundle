<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder;

use PHPUnit\Framework\Attributes\CoversClass;
use Psr\Container\ContainerInterface;
use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\MergePdfBuilder;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;

/**
 * @extends GotenbergBuilderTestCase<MergePdfBuilder>
 */
#[CoversClass(MergePdfBuilder::class)]
class MergePdfBuilderTest extends GotenbergBuilderTestCase
{
    protected function createBuilder(GotenbergClientInterface $client, ContainerInterface $dependencies): BuilderInterface
    {
        $dependencies->set('asset_base_dir_formatter', new AssetBaseDirFormatter(__DIR__, 'fixtures'));

        return new MergePdfBuilder($client, $dependencies);
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
        $this->expectException(InvalidBuilderConfiguration::class);
        $this->expectExceptionMessage('The option "files" expects files with a "pdf" extension, but "'.__DIR__.'/fixtures/b.png" has a "png" extension.');

        $this->getBuilder()
            ->files('a.pdf', 'b.png')
            ->generate()
        ;
    }
}
