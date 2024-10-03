<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Screenshot;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\HtmlScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\ChromiumScreenshotTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\GotenbergBuilderTestCase;
use Sensiolabs\GotenbergBundle\Twig\GotenbergRuntime;
use Symfony\Component\DependencyInjection\Container;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\RuntimeLoader\RuntimeLoaderInterface;

/**
 * @extends GotenbergBuilderTestCase<HtmlScreenshotBuilder>
 */
final class HtmlScreenshotBuilderTest extends GotenbergBuilderTestCase
{
    /** @use ChromiumScreenshotTestCaseTrait<HtmlScreenshotBuilder> */
    use ChromiumScreenshotTestCaseTrait;

    protected function createBuilder(GotenbergClientInterface $client, Container $dependencies): HtmlScreenshotBuilder
    {
        return new HtmlScreenshotBuilder($client, $dependencies);
    }

    /**
     * @param HtmlScreenshotBuilder $builder
     */
    protected function initializeBuilder(BuilderInterface $builder, Container $container): HtmlScreenshotBuilder
    {
        return $builder
            ->contentFile('files/content.html')
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

        $this->assertGotenbergEndpoint('/forms/chromium/screenshot/html');
        $this->assertGotenbergHeader('Gotenberg-Output-Filename', 'test');
    }

    public function testWithTwigContentFile(): void
    {
        $twig = new Environment(new FilesystemLoader(self::FIXTURE_DIR), [
            'strict_variables' => true,
        ]);

        $twig->addRuntimeLoader(new class implements RuntimeLoaderInterface {
            public function load(string $class): object|null
            {
                return GotenbergRuntime::class === $class ? new GotenbergRuntime() : null;
            }
        });

        $this->dependencies->set('twig', $twig);

        $this->getBuilder()
            ->content('templates/content.html.twig', ['name' => 'world'])
            ->generate()
        ;

        $this->assertGotenbergEndpoint('/forms/chromium/screenshot/html');

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

    public function testRequiredFormData(): void
    {
        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('Content is required');

        $this->getBuilder()
            ->generate()
        ;
    }
}
