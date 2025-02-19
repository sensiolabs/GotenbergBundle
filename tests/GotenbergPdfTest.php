<?php

namespace Sensiolabs\GotenbergBundle\Tests;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class GotenbergPdfTest extends KernelTestCase
{
    public function testUrlBuilderFactory(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        /** @var GotenbergPdfInterface $gotenberg */
        $gotenberg = $container->get(GotenbergPdfInterface::class);
        $builder = $gotenberg->url();
        $builder->nativePageRanges('1-5');

        $data = $builder->getBodyBag()->all();

        self::assertCount(1, $data);

        self::assertArrayHasKey('nativePageRanges', $data);
        self::assertSame('1-5', $data['nativePageRanges']);
    }

    public function testHtmlBuilderFactory(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        /** @var GotenbergPdfInterface $gotenberg */
        $gotenberg = $container->get(GotenbergPdfInterface::class);
        $builder = $gotenberg->html();
        $builder
            ->marginTop(3)
            ->marginBottom(1)
        ;

        $data = $builder->getBodyBag()->all();

        self::assertCount(2, $data);

        self::assertArrayHasKey('marginTop', $data);
        self::assertSame('3in', $data['marginTop']);

        self::assertArrayHasKey('marginBottom', $data);
        self::assertSame('1in', $data['marginBottom']);
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
        $data = $builder->getBodyBag()->all();

        self::assertCount(2, $data);

        self::assertArrayHasKey('files', $data);
        self::assertIsArray($data['files']);

        $file = array_shift($data['files']);
        self::assertInstanceOf(\SplFileInfo::class, $file);
        self::assertSame('file.md', $file->getFilename());

        self::assertArrayHasKey('index.html', $data);
        self::assertInstanceOf(\SplFileInfo::class, $data['index.html']);
        self::assertSame('wrapper.html', $data['index.html']->getFilename());
    }

    public function testOfficeBuilderFactory(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        /** @var GotenbergPdfInterface $gotenberg */
        $gotenberg = $container->get(GotenbergPdfInterface::class);

        $builder = $gotenberg->office();
        $builder->nativePageRanges('1-5');
        $builder->files(__DIR__.'/Fixtures/assets/office/document.odt');

        $data = $builder->getBodyBag()->all();

        self::assertCount(2, $data);

        self::assertArrayHasKey('nativePageRanges', $data);
        self::assertSame('1-5', $data['nativePageRanges']);

        self::assertArrayHasKey('files', $data);
        self::assertIsArray($data['files']);

        $file = array_shift($data['files']);
        self::assertInstanceOf(\SplFileInfo::class, $file);
        self::assertSame('document.odt', $file->getFilename());
    }
}
