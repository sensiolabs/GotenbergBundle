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

#[CoversClass(HtmlScreenshotBuilder::class)]
#[UsesClass(AbstractScreenshotBuilder::class)]
#[UsesClass(AbstractChromiumScreenshotBuilder::class)]
#[UsesClass(AssetBaseDirFormatter::class)]
final class HtmlScreenshotBuilderTest extends AbstractBuilderTestCase
{
    public function testEndpointIsCorrect(): void
    {
        self::$gotenbergClient
            ->expects($this->once())
            ->method('call')
            ->with(
                $this->equalTo('/forms/chromium/screenshot/html'),
                $this->anything(),
                $this->anything(),
            )
        ;
        $builder = $this->getHtmlScreenshotBuilder();
        $builder->contentFile('files/content.html');
        $builder->generate();
    }

    public static function withPlainContentFileProvider(): \Generator
    {
        yield 'with twig' => [true];
        yield 'without twig' => [false];
    }

    #[DataProvider('withPlainContentFileProvider')]
    public function testWithPlainContentFile(bool $withTwig): void
    {
        $builder = $this->getHtmlScreenshotBuilder(true === $withTwig ? null : false);
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

        $this->assertFile($data, 'index.html', $expected);
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

        $this->assertFile($data, 'index.html', $expected);
    }

    public function testRequiredFormData(): void
    {
        $builder = $this->getHtmlScreenshotBuilder();

        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('Content is required');

        $builder->getMultipartFormData();
    }

    private function getHtmlScreenshotBuilder(false|null $twig = null): HtmlScreenshotBuilder
    {
        return new HtmlScreenshotBuilder(self::$gotenbergClient, self::$assetBaseDirFormatter, null === $twig ? self::$twig : null);
    }
}
