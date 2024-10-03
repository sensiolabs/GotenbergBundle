<?php

namespace Sensiolabs\GotenbergBundle\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use Sensiolabs\GotenbergBundle\BuilderOld\Pdf\AbstractChromiumPdfBuilder;
use Sensiolabs\GotenbergBundle\BuilderOld\Pdf\AbstractPdfBuilder;
use Sensiolabs\GotenbergBundle\BuilderOld\Pdf\HtmlPdfBuilder;
use Sensiolabs\GotenbergBundle\BuilderOld\Pdf\LibreOfficePdfBuilder;
use Sensiolabs\GotenbergBundle\BuilderOld\Pdf\MarkdownPdfBuilder;
use Sensiolabs\GotenbergBundle\BuilderOld\Pdf\UrlPdfBuilder;
use Sensiolabs\GotenbergBundle\Client\GotenbergClient;
use Sensiolabs\GotenbergBundle\Debug\Builder\TraceablePdfBuilder;
use Sensiolabs\GotenbergBundle\Debug\TraceableGotenbergPdf;
use Sensiolabs\GotenbergBundle\DependencyInjection\CompilerPass\GotenbergPass;
use Sensiolabs\GotenbergBundle\DependencyInjection\Configuration;
use Sensiolabs\GotenbergBundle\DependencyInjection\SensiolabsGotenbergExtension;
use Sensiolabs\GotenbergBundle\Enumeration\Unit;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Sensiolabs\GotenbergBundle\GotenbergPdf;
use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;
use Sensiolabs\GotenbergBundle\SensiolabsGotenbergBundle;
use Sensiolabs\GotenbergBundle\Webhook\WebhookConfigurationRegistry;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Mime\Part\DataPart;

#[CoversClass(GotenbergPdf::class)]
#[UsesClass(AbstractChromiumPdfBuilder::class)]
#[UsesClass(AbstractPdfBuilder::class)]
#[UsesClass(HtmlPdfBuilder::class)]
#[UsesClass(MarkdownPdfBuilder::class)]
#[UsesClass(LibreOfficePdfBuilder::class)]
#[UsesClass(UrlPdfBuilder::class)]
#[UsesClass(GotenbergClient::class)]
#[UsesClass(AssetBaseDirFormatter::class)]
#[UsesClass(Filesystem::class)]
#[UsesClass(TraceablePdfBuilder::class)]
#[UsesClass(TraceableGotenbergPdf::class)]
#[UsesClass(GotenbergPass::class)]
#[UsesClass(Configuration::class)]
#[UsesClass(SensiolabsGotenbergExtension::class)]
#[UsesClass(SensiolabsGotenbergBundle::class)]
#[UsesClass(Unit::class)]
#[UsesClass(WebhookConfigurationRegistry::class)]
final class GotenbergPdfTest extends KernelTestCase
{
    public function testUrlBuilderFactory(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        /** @var GotenbergPdfInterface $gotenberg */
        $gotenberg = $container->get(GotenbergPdfInterface::class);
        $builder = $gotenberg->url();
        $builder
            ->setConfigurations([
                'native_page_ranges' => '1-5',
            ])
            ->url('https://google.com')
        ;

        self::assertSame([
            ['failOnHttpStatusCodes' => '[499,599]'],
            ['failOnResourceHttpStatusCodes' => '[]'],
            ['nativePageRanges' => '1-5'],
            ['url' => 'https://google.com'],
        ], $builder->getMultipartFormData());
    }

    public function testHtmlBuilderFactory(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        /** @var GotenbergPdfInterface $gotenberg */
        $gotenberg = $container->get(GotenbergPdfInterface::class);
        $builder = $gotenberg->html()
            ->setConfigurations([
                'margin_top' => 3,
                'margin_bottom' => 1,
            ])
        ;
        $builder->contentFile(__DIR__.'/../Fixtures/files/content.html');
        $multipartFormData = $builder->getMultipartFormData();

        self::assertCount(5, $multipartFormData);

        self::assertArrayHasKey(0, $multipartFormData);
        self::assertSame(['failOnHttpStatusCodes' => '[499,599]'], $multipartFormData[0]);

        self::assertArrayHasKey(1, $multipartFormData);
        self::assertSame(['failOnResourceHttpStatusCodes' => '[]'], $multipartFormData[1]);

        self::assertArrayHasKey(2, $multipartFormData);
        self::assertSame(['marginTop' => '3in'], $multipartFormData[2]);

        self::assertArrayHasKey(3, $multipartFormData);
        self::assertSame(['marginBottom' => '1in'], $multipartFormData[3]);

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

        /** @var GotenbergPdfInterface $gotenberg */
        $gotenberg = $container->get(GotenbergPdfInterface::class);

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

    public function testOfficeBuilderFactory(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        /** @var GotenbergPdfInterface $gotenberg */
        $gotenberg = $container->get(GotenbergPdfInterface::class);
        $builder = $gotenberg->office()
            ->setConfigurations([
                'native_page_ranges' => '1-5',
            ])
        ;
        $builder->files(__DIR__.'/Fixtures/assets/office/document.odt');
        $multipartFormData = $builder->getMultipartFormData();

        self::assertCount(2, $multipartFormData);

        self::assertArrayHasKey(0, $multipartFormData);
        self::assertSame(['nativePageRanges' => '1-5'], $multipartFormData[0]);

        self::assertArrayHasKey(1, $multipartFormData);
        self::assertIsArray($multipartFormData[1]);
        self::assertArrayHasKey('files', $multipartFormData[1]);
        self::assertInstanceOf(DataPart::class, $multipartFormData[1]['files']);
        self::assertSame('document.odt', $multipartFormData[1]['files']->getFilename());
    }
}
