<?php

namespace Sensiolabs\GotenbergBundle\Tests\Twig;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Builder\Pdf\AbstractChromiumPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\AbstractChromiumScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Twig\GotenbergAssetRuntime;

#[CoversClass(GotenbergAssetRuntime::class)]
class GotenbergAssetRuntimeTest extends TestCase
{
    public function testGetAssetThrowsPerDefault(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('You need to extend from AbstractChromiumPdfBuilder to use "gotenberg_asset" function.');
        $runtime = new GotenbergAssetRuntime();
        $runtime->getAssetUrl('foo');
    }

    public function testGetAssetThrowsWhenBuilderIsNotSet(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('You need to extend from AbstractChromiumPdfBuilder to use "gotenberg_asset" function.');
        $runtime = new GotenbergAssetRuntime();
        $runtime->setBuilder(null);
        $runtime->getAssetUrl('foo');
    }

    public function testGetAssetCallChromiumPdfBuilder(): void
    {
        $runtime = new GotenbergAssetRuntime();
        $builder = $this->createMock(AbstractChromiumPdfBuilder::class);
        $builder
            ->expects($this->once())
            ->method('addAsset')
            ->with('foo')
        ;
        $runtime->setBuilder($builder);
        $this->assertSame('foo', $runtime->getAssetUrl('foo'));
    }

    public function testGetAssetCallChromiumScreenshotBuilder(): void
    {
        $runtime = new GotenbergAssetRuntime();
        $builder = $this->createMock(AbstractChromiumScreenshotBuilder::class);
        $builder
            ->expects($this->once())
            ->method('addAsset')
            ->with('foo')
        ;
        $runtime->setBuilder($builder);
        $this->assertSame('foo', $runtime->getAssetUrl('foo'));
    }
}
