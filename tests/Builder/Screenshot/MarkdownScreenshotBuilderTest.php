<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Screenshot;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use Sensiolabs\GotenbergBundle\Builder\GotenbergFileResult;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\AbstractChromiumScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\AbstractScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\MarkdownScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Sensiolabs\GotenbergBundle\Processor\NullProcessor;
use Sensiolabs\GotenbergBundle\Tests\Builder\AbstractBuilderTestCase;
use Symfony\Component\HttpFoundation\RequestStack;

#[CoversClass(MarkdownScreenshotBuilder::class)]
#[UsesClass(AbstractChromiumScreenshotBuilder::class)]
#[UsesClass(AbstractScreenshotBuilder::class)]
#[UsesClass(AssetBaseDirFormatter::class)]
#[UsesClass(GotenbergFileResult::class)]
final class MarkdownScreenshotBuilderTest extends AbstractBuilderTestCase
{
    public function testEndpointIsCorrect(): void
    {
        $this->gotenbergClient
            ->expects($this->once())
            ->method('call')
            ->with(
                $this->equalTo('/forms/chromium/screenshot/markdown'),
                $this->anything(),
                $this->anything(),
            )
        ;

        $this->getMarkdownScreenshotBuilder()
            ->wrapperFile('files/wrapper.html')
            ->files('assets/file.md')
            ->generate()
        ;
    }

    public function testMarkdownFile(): void
    {
        $builder = $this->getMarkdownScreenshotBuilder();
        $builder
            ->wrapperFile('files/wrapper.html')
            ->files('assets/file.md')
        ;

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

    public function testRequiredWrapperTemplate(): void
    {
        $builder = $this->getMarkdownScreenshotBuilder();
        $builder
            ->files('assets/file.md')
        ;

        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('HTML template is required');

        $builder->getMultipartFormData();
    }

    public function testRequiredMarkdownFile(): void
    {
        $builder = $this->getMarkdownScreenshotBuilder();
        $builder
            ->wrapperFile('files/wrapper.html')
        ;

        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('At least one markdown file is required');

        $builder->getMultipartFormData();
    }

    private function getMarkdownScreenshotBuilder(bool $twig = true): MarkdownScreenshotBuilder
    {
        return (new MarkdownScreenshotBuilder($this->gotenbergClient, self::$assetBaseDirFormatter, new RequestStack(), $this->webhookConfigurationRegistry, true === $twig ? self::$twig : null))
            ->processor(new NullProcessor())
        ;
    }
}
