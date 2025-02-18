<?php

namespace Sensiolabs\GotenbergBundle\Tests\Twig;

use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\HtmlScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Twig\GotenbergAssetRuntime;

class GotenbergAssetRuntimeTest extends TestCase
{
    public function testGetAssetThrowsPerDefault(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('The gotenberg_asset function must be used in a Gotenberg context.');
        $runtime = new GotenbergAssetRuntime();
        $runtime->getAssetUrl('foo');
    }

    public function testGetAssetThrowsWhenBuilderIsNotSet(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('The gotenberg_asset function must be used in a Gotenberg context.');
        $runtime = new GotenbergAssetRuntime();
        $runtime->setBuilder(null);
        $runtime->getAssetUrl('foo');
    }

//    public function testGetAssetCallChromiumPdfBuilder(): void
//    {
//        $runtime = new GotenbergAssetRuntime();
//        $builder = $this->createMock(HtmlPdfBuilder::class);
//        $builder
//            ->expects($this->once())
//            ->method('addAsset')
//            ->with('foo')
//        ;
//        $runtime->setBuilder($builder);
//        $this->assertSame('foo', $runtime->getAssetUrl('foo'));
//    }

    public function testGetAssetCallChromiumScreenshotBuilder(): void
    {
        $runtime = new GotenbergAssetRuntime();
        $builder = $this->createMock(HtmlScreenshotBuilder::class);
        $builder
            ->expects($this->once())
            ->method('addAsset')
            ->with('foo')
        ;
        $runtime->setBuilder($builder);
        $this->assertSame('foo', $runtime->getAssetUrl('foo'));
    }
}
