<?php

namespace Sensiolabs\GotenbergBundle\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use Sensiolabs\GotenbergBundle\BuilderOld\Screenshot\AbstractChromiumScreenshotBuilder;
use Sensiolabs\GotenbergBundle\BuilderOld\Screenshot\AbstractScreenshotBuilder;
use Sensiolabs\GotenbergBundle\BuilderOld\Screenshot\HtmlScreenshotBuilder;
use Sensiolabs\GotenbergBundle\BuilderOld\Screenshot\MarkdownScreenshotBuilder;
use Sensiolabs\GotenbergBundle\BuilderOld\Screenshot\UrlScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Client\GotenbergClient;
use Sensiolabs\GotenbergBundle\Debug\Builder\TraceableScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Debug\TraceableGotenbergScreenshot;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Sensiolabs\GotenbergBundle\GotenbergScreenshot;
use Sensiolabs\GotenbergBundle\GotenbergScreenshotInterface;
use Sensiolabs\GotenbergBundle\Webhook\WebhookConfigurationRegistry;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Mime\Part\DataPart;

#[CoversClass(GotenbergScreenshot::class)]
#[UsesClass(AbstractScreenshotBuilder::class)]
#[UsesClass(AbstractChromiumScreenshotBuilder::class)]
#[UsesClass(HtmlScreenshotBuilder::class)]
#[UsesClass(MarkdownScreenshotBuilder::class)]
#[UsesClass(UrlScreenshotBuilder::class)]
#[UsesClass(GotenbergClient::class)]
#[UsesClass(AssetBaseDirFormatter::class)]
#[UsesClass(Filesystem::class)]
#[UsesClass(TraceableScreenshotBuilder::class)]
#[UsesClass(TraceableGotenbergScreenshot::class)]
#[UsesClass(WebhookConfigurationRegistry::class)]
final class GotenbergScreenshotTest extends KernelTestCase
{
    public function testUrlBuilderFactory(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        /** @var GotenbergScreenshotInterface $gotenberg */
        $gotenberg = $container->get(GotenbergScreenshotInterface::class);
        $builder = $gotenberg->url();
        $builder
            ->setConfigurations([
                'width' => 500,
                'height' => 500,
            ])
            ->url('https://google.com')
        ;

        self::assertSame([
            ['failOnHttpStatusCodes' => '[499,599]'],
            ['failOnResourceHttpStatusCodes' => '[]'],
            ['width' => '500'],
            ['height' => '500'],
            ['url' => 'https://google.com'],
        ], $builder->getMultipartFormData());
    }

    public function testHtmlBuilderFactory(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        /** @var GotenbergScreenshotInterface $gotenberg */
        $gotenberg = $container->get(GotenbergScreenshotInterface::class);
        $builder = $gotenberg->html()
            ->setConfigurations([
                'format' => 'jpeg',
                'quality' => 50,
            ])
        ;
        $builder->contentFile(__DIR__.'/../Fixtures/files/content.html');
        $multipartFormData = $builder->getMultipartFormData();

        self::assertCount(5, $multipartFormData);

        self::assertArrayHasKey(2, $multipartFormData);
        self::assertSame(['format' => 'jpeg'], $multipartFormData[2]);

        self::assertArrayHasKey(3, $multipartFormData);
        self::assertSame(['quality' => '50'], $multipartFormData[3]);

        self::assertArrayHasKey(4, $multipartFormData);
        self::assertIsArray($multipartFormData[4]);
        self::assertCount(1, $multipartFormData[4]);
        self::assertArrayHasKey('files', $multipartFormData[4]);
        self::assertInstanceOf(DataPart::class, $multipartFormData[4]['files']);
        self::assertSame('index.html', $multipartFormData[4]['files']->getFilename());
    }

    public function testMarkdownBuilderFactory(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        /** @var GotenbergScreenshotInterface $gotenberg */
        $gotenberg = $container->get(GotenbergScreenshotInterface::class);

        $builder = $gotenberg->markdown();
        $builder->files(__DIR__.'/Fixtures/assets/file.md');
        $builder->wrapperFile(__DIR__.'/Fixtures/files/wrapper.html');
        $multipartFormData = $builder->getMultipartFormData();

        self::assertCount(4, $multipartFormData);

        self::assertArrayHasKey(2, $multipartFormData);
        self::assertIsArray($multipartFormData[2]);
        self::assertArrayHasKey('files', $multipartFormData[2]);
        self::assertInstanceOf(DataPart::class, $multipartFormData[2]['files']);
        self::assertSame('file.md', $multipartFormData[2]['files']->getFilename());

        self::assertArrayHasKey(3, $multipartFormData);
        self::assertIsArray($multipartFormData[3]);
        self::assertArrayHasKey('files', $multipartFormData[3]);
        self::assertInstanceOf(DataPart::class, $multipartFormData[3]['files']);
        self::assertSame('index.html', $multipartFormData[3]['files']->getFilename());
    }
}
