<?php

namespace Sensiolabs\GotenbergBundle\Tests\Pdf;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Builder\MarkdownPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\OfficePdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\TwigPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\UrlPdfBuilder;
use Sensiolabs\GotenbergBundle\Client\GotenbergClient;
use Sensiolabs\GotenbergBundle\Client\PdfResponse;
use Sensiolabs\GotenbergBundle\Pdf\Gotenberg;
use Sensiolabs\GotenbergBundle\Tests\Fixtures\Builder\ValidBuilder;
use Sensiolabs\GotenbergBundle\Tests\Fixtures\Builder\WrongBuilder;
use Twig\Environment;
use TypeError;

#[CoversClass(Gotenberg::class)]
#[UsesClass(ValidBuilder::class)]
#[UsesClass(WrongBuilder::class)]
class GotenbergTest extends TestCase
{
    public function testGenerateWithValidCustomBuilder()
    {
        $builder = new ValidBuilder();
        $gotenberg = $this->createMock(Gotenberg::class);

        $response = $gotenberg->generate($builder);

        $this->assertInstanceOf(PdfResponse::class, $response);
    }

    public function testGenerateWithInvalidCustomBuilder()
    {
        $this->expectException(TypeError::class);

        $builder = new WrongBuilder();
        $gotenberg = $this->createMock(Gotenberg::class);
        $gotenberg->generate($builder);
    }

    public function testAllBuildersFactory()
    {
        $gotenbergClient = $this->createMock(GotenbergClient::class);
        $twig = $this->createMock(Environment::class);

        $gotenberg = new Gotenberg($gotenbergClient, $twig, [], __DIR__.'/../Fixtures');

        $this->assertInstanceOf(TwigPdfBuilder::class, $gotenberg->twig());
        $this->assertInstanceOf(UrlPdfBuilder::class, $gotenberg->url());
        $this->assertInstanceOf(MarkdownPdfBuilder::class, $gotenberg->markdown());
        $this->assertInstanceOf(OfficePdfBuilder::class, $gotenberg->office());
    }
}
