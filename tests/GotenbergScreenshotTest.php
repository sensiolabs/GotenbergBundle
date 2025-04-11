<?php

namespace Sensiolabs\GotenbergBundle\Tests;

use Sensiolabs\GotenbergBundle\GotenbergScreenshotInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

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
            ->width(500)
            ->height(200)
        ;

        $data = $builder->getBodyBag()->all();

        self::assertArrayHasKey('width', $data);
        self::assertSame(500, $data['width']);

        self::assertArrayHasKey('height', $data);
        self::assertSame(200, $data['height']);
    }

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

    public function testMarkdownBuilderFactory(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        /** @var GotenbergScreenshotInterface $gotenberg */
        $gotenberg = $container->get(GotenbergScreenshotInterface::class);

        $builder = $gotenberg->markdown();
        $builder
            ->files(__DIR__.'/Fixtures/assets/file.md')
            ->wrapperFile(__DIR__.'/Fixtures/files/wrapper.html')
            ->width(500)
            ->height(200)
        ;

        $data = $builder->getBodyBag()->all();

        self::assertArrayHasKey('files', $data);

        $files = $data['files'];
        self::assertArrayHasKey(__DIR__.'/Fixtures/assets/file.md', $files);
        self::assertInstanceOf(\SplFileInfo::class, $files[__DIR__.'/Fixtures/assets/file.md']);

        self::assertArrayHasKey('index.html', $data);
        self::assertInstanceOf(\SplFileInfo::class, $data['index.html']);

        self::assertArrayHasKey('width', $data);
        self::assertSame(500, $data['width']);

        self::assertArrayHasKey('width', $data);
        self::assertSame(500, $data['width']);

        self::assertArrayHasKey('height', $data);
        self::assertSame(200, $data['height']);
    }
}
