<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder;

use PHPUnit\Framework\Attributes\CoversClass;
use Psr\Container\ContainerInterface;
use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\HtmlPdfBuilder;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Sensiolabs\GotenbergBundle\Twig\GotenbergAssetRuntime;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\RuntimeLoader\RuntimeLoaderInterface;

/**
 * @extends GotenbergBuilderTestCase<HtmlPdfBuilder>
 */
#[CoversClass(HtmlPdfBuilder::class)]
class HtmlPdfBuilderTest extends GotenbergBuilderTestCase
{
    protected function createBuilder(GotenbergClientInterface $client, ContainerInterface $dependencies): BuilderInterface
    {
        return new HtmlPdfBuilder($client, $dependencies);
    }

    public function testRequiredFormData(): void
    {
        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('Content is required');

        $this->getBuilder()
            ->generate()
        ;
    }

    public function testFilename(): void
    {
        $this->dependencies->set('asset_base_dir_formatter', new AssetBaseDirFormatter(__DIR__, '/../Fixtures'));

        $this->getBuilder()
            ->contentFile('content.html')
            ->filename('test')
            ->generate()
        ;

        $this->assertGotenbergEndpoint('/forms/chromium/convert/html');
        $this->assertGotenbergHeader('Gotenberg-Output-Filename', 'test');
    }

    public function testWithTwigContentFile(): void
    {
        $this->dependencies->set('asset_base_dir_formatter', new AssetBaseDirFormatter(__DIR__, '/../Fixtures'));

        $twig = new Environment(new FilesystemLoader(__DIR__.'/../Fixtures'), [
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
            ->content('templates/content.html.twig', ['name' => 'world'])
            ->generate()
        ;

        $expected = <<<HTML
        <!DOCTYPE html>
        <html lang="en">
            <head>
                <meta charset="utf-8" />
                <title>My PDF</title>
            </head>
            <body>
                <h1>Hello world!</h1>
                <img src="logo.png" />
            </body>
        </html>

        HTML;

        $this->assertContentFile('index.html', 'text/html', $expected);
    }
}
