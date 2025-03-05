<?php

namespace Sensiolabs\GotenbergBundle\Tests\Twig;

use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Builder\BuilderAssetInterface;
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

    public function testGetAssetWithBuilder(): void
    {
        $runtime = new GotenbergAssetRuntime();
        $runtime->setBuilder(new MyBuilder());
        $path = $runtime->getAssetUrl('path/to/bar.png');

        self::assertSame('bar.png', $path);
    }
}

class MyBuilder implements BuilderAssetInterface
{
    public function addAsset(string $path): static
    {
        return $this;
    }
}
