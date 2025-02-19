<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Pdf\HtmlPdfBuilder;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\ChromiumPdfTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\GotenbergBuilderTestCase;
use Sensiolabs\GotenbergBundle\Twig\GotenbergAssetRuntime;
use Symfony\Component\DependencyInjection\Container;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\RuntimeLoader\RuntimeLoaderInterface;

/**
 * @extends GotenbergBuilderTestCase<HtmlPdfBuilder>
 */
final class HtmlPdfBuilderTest extends GotenbergBuilderTestCase
{
    /** @use ChromiumPdfTestCaseTrait<HtmlPdfBuilder> */
    use ChromiumPdfTestCaseTrait;

    protected function createBuilder(GotenbergClientInterface $client, Container $dependencies): HtmlPdfBuilder
    {
        return new HtmlPdfBuilder($client, $dependencies);
    }

    /**
     * @param HtmlPdfBuilder $builder
     */
    protected function initializeBuilder(BuilderInterface $builder, Container $container): HtmlPdfBuilder
    {
        return $builder
            ->contentFile('files/content.html')
        ;
    }

    public function testRequiredFormData(): void
    {
        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('Content is required');

        $this->getBuilder()
            ->generate()
        ;
    }

    public function testOutputFilename(): void
    {
        $this->dependencies->set('asset_base_dir_formatter', new AssetBaseDirFormatter(self::FIXTURE_DIR, self::FIXTURE_DIR));

        $this->getBuilder()
            ->contentFile('files/content.html')
            ->filename('test')
            ->generate()
        ;

        $this->assertGotenbergEndpoint('/forms/chromium/convert/html');
        $this->assertGotenbergHeader('Gotenberg-Output-Filename', 'test');
    }

    public function testWidth(): void
    {
        $this->dependencies->set('asset_base_dir_formatter', new AssetBaseDirFormatter(self::FIXTURE_DIR, self::FIXTURE_DIR));

        $this->getBuilder()
            ->contentFile('files/content.html')
            ->filename('test')
            ->paperWidth(200)
            ->paperHeight(150)
            ->generate()
        ;

        $this->assertGotenbergFormData('paperWidth', '200in');
        $this->assertGotenbergFormData('paperHeight', '150in');

        $this->assertGotenbergEndpoint('/forms/chromium/convert/html');
        $this->assertGotenbergHeader('Gotenberg-Output-Filename', 'test');
    }

    public function testWithTwigContentFile(): void
    {
        $this->dependencies->set('asset_base_dir_formatter', new AssetBaseDirFormatter(self::FIXTURE_DIR, self::FIXTURE_DIR));

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
