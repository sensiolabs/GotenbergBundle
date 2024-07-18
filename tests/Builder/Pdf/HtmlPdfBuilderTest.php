<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Pdf;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use Sensiolabs\GotenbergBundle\Builder\Pdf\AbstractChromiumPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\AbstractPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\HtmlPdfBuilder;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Sensiolabs\GotenbergBundle\Processor\NullProcessor;
use Sensiolabs\GotenbergBundle\Tests\Builder\AbstractBuilderTestCase;
use Sensiolabs\GotenbergBundle\Twig\GotenbergAssetExtension;
use Symfony\Component\HttpFoundation\RequestStack;

#[CoversClass(HtmlPdfBuilder::class)]
#[UsesClass(AbstractChromiumPdfBuilder::class)]
#[UsesClass(AbstractPdfBuilder::class)]
#[UsesClass(AssetBaseDirFormatter::class)]
#[UsesClass(GotenbergAssetExtension::class)]
final class HtmlPdfBuilderTest extends AbstractBuilderTestCase
{
    public function testEndpointIsCorrect(): void
    {
        $this->gotenbergClient
            ->expects($this->once())
            ->method('call')
            ->with(
                $this->equalTo('/forms/chromium/convert/html'),
                $this->anything(),
                $this->anything(),
            )
        ;

        $this->getHtmlPdfBuilder()
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
        $builder = $this->getHtmlPdfBuilder($withTwig);
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
        $builder = $this->getHtmlPdfBuilder();
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
        $builder = $this->getHtmlPdfBuilder();

        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('Content is required');

        $builder->getMultipartFormData();
    }

    private function getHtmlPdfBuilder(bool $twig = true): HtmlPdfBuilder
    {
        return (new HtmlPdfBuilder($this->gotenbergClient, self::$assetBaseDirFormatter, new RequestStack(), true === $twig ? self::$twig : null))
            ->processor(new NullProcessor())
        ;
    }
}
