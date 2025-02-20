<?php

namespace Sensiolabs\GotenbergBundle\Tests;

use Sensiolabs\GotenbergBundle\GotenbergScreenshotInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class GotenbergScreenshotTest extends KernelTestCase
{
    //    public function testUrlBuilderFactory(): void
    //    {
    //        self::bootKernel();
    //
    //        $container = static::getContainer();
    //
    //        /** @var GotenbergScreenshotInterface $gotenberg */
    //        $gotenberg = $container->get(GotenbergScreenshotInterface::class);
    //        $builder = $gotenberg->url();
    //        $builder
    //            ->setConfigurations([
    //                'width' => 500,
    //                'height' => 500,
    //            ])
    //            ->url('https://google.com')
    //        ;
    //
    //        self::assertSame([
    //            ['failOnHttpStatusCodes' => '[499,599]'],
    //            ['failOnResourceHttpStatusCodes' => '[]'],
    //            ['width' => '500'],
    //            ['height' => '500'],
    //            ['url' => 'https://google.com'],
    //        ], $builder->getMultipartFormData());
    //    }

    public function testHtmlBuilderFactory(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        /** @var GotenbergScreenshotInterface $gotenberg */
        $gotenberg = $container->get(GotenbergScreenshotInterface::class);

        $builder = $gotenberg->html();
        $builder
            ->width(500)
            ->height(200)
        ;

        $data = $builder->getBodyBag()->all();

        self::assertArrayHasKey('width', $data);
        self::assertSame(500, $data['width']);

        self::assertArrayHasKey('height', $data);
        self::assertSame(200, $data['height']);
    }

    //    public function testMarkdownBuilderFactory(): void
    //    {
    //        self::bootKernel();
    //
    //        $container = static::getContainer();
    //
    //        /** @var GotenbergScreenshotInterface $gotenberg */
    //        $gotenberg = $container->get(GotenbergScreenshotInterface::class);
    //
    //        $builder = $gotenberg->markdown();
    //        $builder->files(__DIR__.'/Fixtures/assets/file.md');
    //        $builder->wrapperFile(__DIR__.'/Fixtures/files/wrapper.html');
    //        $multipartFormData = $builder->getMultipartFormData();
    //
    //        self::assertCount(4, $multipartFormData);
    //
    //        self::assertArrayHasKey(2, $multipartFormData);
    //        self::assertIsArray($multipartFormData[2]);
    //        self::assertArrayHasKey('files', $multipartFormData[2]);
    //        self::assertInstanceOf(DataPart::class, $multipartFormData[2]['files']);
    //        self::assertSame('file.md', $multipartFormData[2]['files']->getFilename());
    //
    //        self::assertArrayHasKey(3, $multipartFormData);
    //        self::assertIsArray($multipartFormData[3]);
    //        self::assertArrayHasKey('files', $multipartFormData[3]);
    //        self::assertInstanceOf(DataPart::class, $multipartFormData[3]['files']);
    //        self::assertSame('index.html', $multipartFormData[3]['files']->getFilename());
    //    }
}
