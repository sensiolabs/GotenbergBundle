<?php

namespace Sensiolabs\GotenbergBundle\Tests\Pdf;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Pdf\Gotenberg;
use Symfony\Component\Mime\Part\DataPart;
use Twig\Environment;

#[CoversClass(Gotenberg::class)]
final class GotenbergTest extends TestCase
{
    public function testUrlBuilderFactory(): void
    {
        $gotenbergClient = $this->createMock(GotenbergClientInterface::class);

        $gotenberg = new Gotenberg(
            $gotenbergClient,
            ['native_page_ranges' => '1-5'],
            __DIR__.'/../Fixtures',
        );
        $builder = $gotenberg->url('https://google.com');

        self::assertSame([['nativePageRanges' => '1-5'], ['url' => 'https://google.com']], $builder->getMultipartFormData());
    }

    public function testHtmlBuilderFactory(): void
    {
        $gotenbergClient = $this->createMock(GotenbergClientInterface::class);
        $twig = $this->createMock(Environment::class);

        $gotenberg = new Gotenberg(
            $gotenbergClient,
            ['margin_top' => 3, 'margin_bottom' => 1],
            __DIR__.'/../Fixtures',
            $twig,
        );
        $builder = $gotenberg->html();
        $builder->contentFile('content.html');
        $multipartFormData = $builder->getMultipartFormData();

        self::assertCount(3, $multipartFormData);

        self::assertArrayHasKey(0, $multipartFormData);
        self::assertSame(['marginTop' => 3.0], $multipartFormData[0]);

        self::assertArrayHasKey(1, $multipartFormData);
        self::assertSame(['marginBottom' => 1.0], $multipartFormData[1]);

        self::assertArrayHasKey(2, $multipartFormData);
        self::assertIsArray($multipartFormData[2]);
        self::assertCount(1, $multipartFormData[2]);
        self::assertArrayHasKey('files', $multipartFormData[2]);
        self::assertInstanceOf(DataPart::class, $multipartFormData[2]['files']);
        self::assertSame('index.html', $multipartFormData[2]['files']->getFilename());
    }

    public function testMarkdownBuilderFactory(): void
    {
        $gotenbergClient = $this->createMock(GotenbergClientInterface::class);
        $twig = $this->createMock(Environment::class);

        $gotenberg = new Gotenberg(
            $gotenbergClient,
            [],
            __DIR__.'/../Fixtures',
            $twig,
        );
        $builder = $gotenberg->markdown('assets/file.md');
        $builder->htmlWrapperFile('wrapper.html');
        $multipartFormData = $builder->getMultipartFormData();

        self::assertCount(2, $multipartFormData);

        self::assertArrayHasKey(0, $multipartFormData);
        self::assertIsArray($multipartFormData[0]);
        self::assertArrayHasKey('files', $multipartFormData[0]);
        self::assertInstanceOf(DataPart::class, $multipartFormData[0]['files']);
        self::assertSame('file.md', $multipartFormData[0]['files']->getFilename());

        self::assertArrayHasKey(1, $multipartFormData);
        self::assertIsArray($multipartFormData[1]);
        self::assertArrayHasKey('files', $multipartFormData[1]);
        self::assertInstanceOf(DataPart::class, $multipartFormData[1]['files']);
        self::assertSame('index.html', $multipartFormData[1]['files']->getFilename());
    }

    public function testOfficeBuilderFactory(): void
    {
        $gotenbergClient = $this->createMock(GotenbergClientInterface::class);
        $twig = $this->createMock(Environment::class);

        $gotenberg = new Gotenberg(
            $gotenbergClient,
            ['native_page_ranges' => '1-5'],
            __DIR__.'/../Fixtures',
            $twig,
        );
        $builder = $gotenberg->office('assets/office/document.odt');
        $multipartFormData = $builder->getMultipartFormData();

        self::assertCount(2, $multipartFormData);

        self::assertArrayHasKey(0, $multipartFormData);
        self::assertIsArray($multipartFormData[0]);
        self::assertArrayHasKey('files', $multipartFormData[0]);
        self::assertInstanceOf(DataPart::class, $multipartFormData[0]['files']);
        self::assertSame('document.odt', $multipartFormData[0]['files']->getFilename());

        self::assertArrayHasKey(1, $multipartFormData);
        self::assertSame(['nativePageRanges' => '1-5'], $multipartFormData[1]);
    }
}
