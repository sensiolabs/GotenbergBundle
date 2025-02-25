<?php

namespace Sensiolabs\GotenbergBundle\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use Sensiolabs\GotenbergBundle\Enumeration\PdfFormat;
use Sensiolabs\GotenbergBundle\Enumeration\SplitMode;
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

    /**
     * @return iterable<string, array<int, string>>
     */
    public static function provideFileToConvert(): iterable
    {
        yield 'convert odt file' => [__DIR__.'/Fixtures/assets/office/document.odt', 'document.odt'];
        yield 'convert docx file' => [__DIR__.'/Fixtures/assets/office/document_1.docx', 'document_1.docx'];
        yield 'convert html file' => [__DIR__.'/Fixtures/assets/office/document_2.html', 'document_2.html'];
        yield 'convert xlsx file' => [__DIR__.'/Fixtures/assets/office/document_3.xlsx', 'document_3.xlsx'];
        yield 'convert pptx file' => [__DIR__.'/Fixtures/assets/office/document_4.pptx', 'document_4.pptx'];
    }

    #[DataProvider('provideFileToConvert')]
    public function testOfficeBuilderFactory(string $path, string $filename): void
    {
        self::bootKernel();

        $container = static::getContainer();

        /** @var GotenbergPdfInterface $gotenberg */
        $gotenberg = $container->get(GotenbergPdfInterface::class);

        $builder = $gotenberg->office();
        $builder->files($path);
        $data = $builder->getBodyBag()->all();

        self::assertCount(1, $data);

        self::assertArrayHasKey('files', $data);
        self::assertIsArray($data['files']);

        $firstFile = array_shift($data['files']);
        self::assertInstanceOf(\SplFileInfo::class, $firstFile);
        self::assertSame($filename, $firstFile->getFilename());
    }

    public function testMergeBuilderFactory(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        /** @var GotenbergPdfInterface $gotenberg */
        $gotenberg = $container->get(GotenbergPdfInterface::class);

        $builder = $gotenberg->merge();
        $builder->files(
            __DIR__.'/Fixtures/assets/pdf/document.pdf',
            __DIR__.'/Fixtures/assets/pdf/other_document.pdf',
        );
        $builder->pdfUniversalAccess();
        $data = $builder->getBodyBag()->all();

        self::assertCount(2, $data);

        self::assertArrayHasKey('files', $data);
        self::assertIsArray($data['files']);

        $firstFile = array_shift($data['files']);
        self::assertInstanceOf(\SplFileInfo::class, $firstFile);
        self::assertSame('document.pdf', $firstFile->getFilename());

        $lastFile = array_pop($data['files']);
        self::assertInstanceOf(\SplFileInfo::class, $lastFile);
        self::assertSame('other_document.pdf', $lastFile->getFilename());

        self::assertArrayHasKey('pdfua', $data);
        self::assertTrue($data['pdfua']);
    }

    public function testConvertBuilderFactory(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        /** @var GotenbergPdfInterface $gotenberg */
        $gotenberg = $container->get(GotenbergPdfInterface::class);

        $builder = $gotenberg->convert();
        $builder->files(__DIR__.'/Fixtures/assets/pdf/document.pdf');
        $builder->pdfFormat(PdfFormat::Pdf1b);
        $data = $builder->getBodyBag()->all();

        self::assertCount(2, $data);

        self::assertArrayHasKey('files', $data);
        self::assertIsArray($data['files']);

        $firstFile = array_shift($data['files']);
        self::assertInstanceOf(\SplFileInfo::class, $firstFile);
        self::assertSame('document.pdf', $firstFile->getFilename());

        self::assertArrayHasKey('pdfa', $data);
        self::assertSame(PdfFormat::Pdf1b, $data['pdfa']);
    }

    public function testSplitBuilderFactory(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        /** @var GotenbergPdfInterface $gotenberg */
        $gotenberg = $container->get(GotenbergPdfInterface::class);

        $builder = $gotenberg->split();
        $builder->files(__DIR__.'/Fixtures/assets/pdf/document.pdf');
        $builder->splitMode(SplitMode::Pages);
        $builder->splitSpan('1-2');
        $builder->splitUnify();

        $data = $builder->getBodyBag()->all();

        self::assertCount(4, $data);

        self::assertArrayHasKey('files', $data);
        self::assertIsArray($data['files']);

        $firstFile = array_shift($data['files']);
        self::assertInstanceOf(\SplFileInfo::class, $firstFile);
        self::assertSame('document.pdf', $firstFile->getFilename());

        self::assertArrayHasKey('splitMode', $data);
        self::assertSame(SplitMode::Pages, $data['splitMode']);

        self::assertArrayHasKey('splitSpan', $data);
        self::assertSame('1-2', $data['splitSpan']);

        self::assertArrayHasKey('splitUnify', $data);
        self::assertTrue($data['splitUnify']);
    }

    public function testFlattenBuilderFactory(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        /** @var GotenbergPdfInterface $gotenberg */
        $gotenberg = $container->get(GotenbergPdfInterface::class);

        $builder = $gotenberg->flatten();
        $builder->files(__DIR__.'/Fixtures/assets/pdf/document.pdf');

        $data = $builder->getBodyBag()->all();

        self::assertCount(1, $data);

        self::assertArrayHasKey('files', $data);
        self::assertIsArray($data['files']);

        $firstFile = array_shift($data['files']);
        self::assertInstanceOf(\SplFileInfo::class, $firstFile);
        self::assertSame('document.pdf', $firstFile->getFilename());
    }
}
