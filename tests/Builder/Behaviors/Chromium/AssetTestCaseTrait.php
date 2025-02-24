<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\Chromium;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\BehaviorTrait;

/**
 * @template T of BuilderInterface
 */
trait AssetTestCaseTrait
{
    /** @use BehaviorTrait<T> */
    use BehaviorTrait;

    abstract protected function assertContentFile(string $filename, string $contentType = 'text/html', string|null $expectedContent = null): void;

    public function testToAddAssets(): void
    {
        $this->getDefaultBuilder()
            ->assets('assets/logo.png')
            ->generate()
        ;

        $this->assertContentFile('logo.png', 'image/png');
    }

    public function testToAddAssetsToExistingAssets(): void
    {
        $this->getDefaultBuilder()
            ->assets('assets/logo.png')
            ->addAsset('assets/other_logo.png')
            ->generate()
        ;

        $this->assertContentFile('logo.png', 'image/png');
        $this->assertContentFile('other_logo.png', 'image/png');
    }

    public function testToAddSameAssetButLoadOnce(): void
    {
        $this->getDefaultBuilder()
            ->assets('assets/logo.png')
            ->addAsset('assets/logo.png')
            ->generate()
        ;

        $this->assertContentFile('logo.png', 'image/png');
    }
}
