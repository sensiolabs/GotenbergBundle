<?php

namespace Sensiolabs\GotenbergBundle\Tests\Pdf;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Client\GotenbergClient;
use Sensiolabs\GotenbergBundle\Client\PdfResponse;
use Sensiolabs\GotenbergBundle\Pdf\Gotenberg;
use Sensiolabs\GotenbergBundle\Tests\Builder\BuilderInterfaceMock;
use Twig\Environment;

#[CoversClass(Gotenberg::class)]
final class GotenbergTest extends TestCase
{
    public function testUrlBuilderFactory(): void
    {
        $gotenbergClient = $this->createMock(GotenbergClient::class);
        $twig = $this->createMock(Environment::class);

        $gotenberg = new Gotenberg($gotenbergClient, ['native_page_ranges' => '1-5'], __DIR__.'/../Fixtures', $twig);
        $urlBuilder = $gotenberg->url();

        self::assertEquals([['nativePageRanges' => '1-5']], $urlBuilder->getMultipartFormData());
    }

    public function testTwigBuilderFactory(): void
    {
        $gotenbergClient = $this->createMock(GotenbergClient::class);
        $twig = $this->createMock(Environment::class);

        $gotenberg = new Gotenberg($gotenbergClient, ['margin_top' => 3, 'margin_bottom' => 1], __DIR__.'/../Fixtures', $twig);
        $twigBuilder = $gotenberg->twig();

        self::assertEquals([['marginTop' => 3], ['marginBottom' => 1]], $twigBuilder->getMultipartFormData());
    }

    public function testMarkdownBuilderFactory(): void
    {
        $gotenbergClient = $this->createMock(GotenbergClient::class);
        $twig = $this->createMock(Environment::class);

        $gotenberg = new Gotenberg($gotenbergClient, [], __DIR__.'/../Fixtures', $twig);
        $markdownBuilder = $gotenberg->markdown();

        self::assertTrue(method_exists($markdownBuilder, 'markdownFile'));
        self::assertEquals([], $markdownBuilder->getMultipartFormData());
    }

    public function testOfficeBuilderFactory(): void
    {
        $gotenbergClient = $this->createMock(GotenbergClient::class);
        $twig = $this->createMock(Environment::class);

        $gotenberg = new Gotenberg($gotenbergClient, ['paper_width' => 11.7, 'paper_height' => 16.54], __DIR__.'/../Fixtures', $twig);
        $officeBuilder = $gotenberg->office();

        self::assertTrue(method_exists($officeBuilder, 'officeFile'));
        self::assertEquals([['paperWidth' => 11.7], ['paperHeight' => 16.54]], $officeBuilder->getMultipartFormData());
    }
}
