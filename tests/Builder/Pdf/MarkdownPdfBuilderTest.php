<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Pdf;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use Psr\Container\ContainerInterface;
use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Pdf\MarkdownPdfBuilder;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Sensiolabs\GotenbergBundle\Tests\Builder\GotenbergBuilderTestCase;
use Sensiolabs\GotenbergBundle\Twig\GotenbergAssetRuntime;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\RuntimeLoader\RuntimeLoaderInterface;

/**
 * @extends GotenbergBuilderTestCase<MarkdownPdfBuilder>
 */
#[CoversClass(MarkdownPdfBuilder::class)]
#[UsesClass(AssetBaseDirFormatter::class)]
#[UsesClass(Environment::class)]
#[UsesClass(FilesystemLoader::class)]
#[UsesClass(GotenbergAssetRuntime::class)]
final class MarkdownPdfBuilderTest extends GotenbergBuilderTestCase
{
    protected function createBuilder(GotenbergClientInterface $client, ContainerInterface $dependencies): BuilderInterface
    {
        $dependencies->set('asset_base_dir_formatter', new AssetBaseDirFormatter(self::FIXTURE_DIR, self::FIXTURE_DIR));

        return new MarkdownPdfBuilder($client, $dependencies);
    }

    public function testFileWithContentFile(): void
    {
        $this->getBuilder()
            ->files('assets/file.md')
            ->contentFile('files/wrapper.html')
            ->generate()
        ;

        $this->assertGotenbergEndpoint('/forms/chromium/convert/markdown');
        $this->assertGotenbergFormDataFile('files', 'text/markdown', self::FIXTURE_DIR.'/assets/file.md');
    }

    public function testFileWithContent(): void
    {
        $twig = new Environment(new FilesystemLoader(self::FIXTURE_DIR), [
            'strict_variables' => true,
        ]);

        $twig->addRuntimeLoader(new class implements RuntimeLoaderInterface {
            public function load(string $class): object|null
            {
                return GotenbergAssetRuntime::class === $class ? new GotenbergAssetRuntime() : null;
            }
        });

        $this->dependencies->set('twig', $twig);

        $this->getBuilder()
            ->files('assets/file.md')
            ->content('templates/wrapper.html.twig', ['name' => 'John Doe'])
            ->generate()
        ;

        $this->assertGotenbergEndpoint('/forms/chromium/convert/markdown');
        $this->assertGotenbergFormDataFile('files', 'text/markdown', self::FIXTURE_DIR.'/assets/file.md');
    }

    public function testRequiredFile(): void
    {
        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('HTML template is required');

        $this->getBuilder()
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
