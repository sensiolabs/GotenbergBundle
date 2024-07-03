<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Screenshot;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\AbstractChromiumScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\AbstractScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\HtmlScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Sensiolabs\GotenbergBundle\Tests\Builder\AbstractBuilderTestCase;
use Sensiolabs\GotenbergBundle\Twig\GotenbergAssetExtension;
use Symfony\Component\HttpFoundation\RequestStack;

#[CoversClass(HtmlScreenshotBuilder::class)]
#[UsesClass(AbstractChromiumScreenshotBuilder::class)]
#[UsesClass(AbstractScreenshotBuilder::class)]
#[UsesClass(AssetBaseDirFormatter::class)]
#[UsesClass(GotenbergAssetExtension::class)]
final class HtmlScreenshotBuilderTest extends AbstractBuilderTestCase
{
    public function testEndpointIsCorrect(): void
    {
        $this->gotenbergClient
            ->expects($this->once())
            ->method('call')
            ->with(
                $this->equalTo('/forms/chromium/screenshot/html'),
                $this->anything(),
                $this->anything(),
            )
        ;

        $this->getHtmlScreenshotBuilder()
            ->contentFile('files/content.html')
            ->generate()
        ;
    }

    public static function withPlainContentFileProvider(): \Generator
    {
        yield 'with twig' => [true];
        yield 'without twig' => [false];
    }

    #[DataProvider('withPlainContentFileProvider')]
    public function testWithPlainContentFile(bool $withTwig): void
    {
        $builder = $this->getHtmlScreenshotBuilder($withTwig);
        $builder->contentFile('files/content.html');

        $data = $builder->getMultipartFormData()[0];

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

        self::assertFile($data, 'index.html', expectedContent: $expected);
    }

    public function testWithTwigContentFile(): void
    {
        $builder = $this->getHtmlScreenshotBuilder();
        $builder->content('templates/content.html.twig', ['name' => 'world']);

        $data = $builder->getMultipartFormData()[0];

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

        self::assertFile($data, 'index.html', expectedContent: $expected);
    }

    public function testRequiredFormData(): void
    {
        $builder = $this->getHtmlScreenshotBuilder();

        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('Content is required');

        $builder->getMultipartFormData();
    }

    private function getHtmlScreenshotBuilder(bool $twig = true): HtmlScreenshotBuilder
    {
        return new HtmlScreenshotBuilder($this->gotenbergClient, self::$assetBaseDirFormatter, new RequestStack(), true === $twig ? self::$twig : null);
    }
}
