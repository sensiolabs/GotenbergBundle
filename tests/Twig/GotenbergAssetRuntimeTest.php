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
}
