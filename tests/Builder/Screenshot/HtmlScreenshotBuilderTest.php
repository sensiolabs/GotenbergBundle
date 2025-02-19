<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Screenshot;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\HtmlScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\ChromiumScreenshotTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\GotenbergBuilderTestCase;
use Symfony\Component\DependencyInjection\Container;

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

//    public static function withPlainContentFileProvider(): \Generator
//    {
//        yield 'with twig' => [true];
//        yield 'without twig' => [false];
//    }
//
//    #[DataProvider('withPlainContentFileProvider')]
//    public function testWithPlainContentFile(bool $withTwig): void
//    {
//        $builder = $this->getHtmlScreenshotBuilder($withTwig);
//        $builder->contentFile('files/content.html');
//
//        $data = $builder->getMultipartFormData()[0];
//
//        $expected = <<<HTML
//        <!DOCTYPE html>
//        <html lang="en">
//            <head>
//                <meta charset="utf-8" />
//                <title>My PDF</title>
//            </head>
//            <body>
//                <h1>Hello world!</h1>
//                <img src="logo.png" />
//            </body>
//        </html>
//
//        HTML;
//
//        self::assertFile($data, 'index.html', expectedContent: $expected);
//    }
//
//    public function testWithTwigContentFile(): void
//    {
//        $builder = $this->getHtmlScreenshotBuilder();
//        $builder->content('templates/content.html.twig', ['name' => 'world']);
//
//        $data = $builder->getMultipartFormData()[0];
//
//        $expected = <<<HTML
//        <!DOCTYPE html>
//        <html lang="en">
//            <head>
//                <meta charset="utf-8" />
//                <title>My PDF</title>
//            </head>
//            <body>
//                <h1>Hello world!</h1>
//                <img src="logo.png" />
//            </body>
//        </html>
//
//        HTML;
//
//        self::assertFile($data, 'index.html', expectedContent: $expected);
//    }
//
//    public function testRequiredFormData(): void
//    {
//        $builder = $this->getHtmlScreenshotBuilder();
//
//        $this->expectException(MissingRequiredFieldException::class);
//        $this->expectExceptionMessage('Content is required');
//
//        $builder->getMultipartFormData();
//    }
}
