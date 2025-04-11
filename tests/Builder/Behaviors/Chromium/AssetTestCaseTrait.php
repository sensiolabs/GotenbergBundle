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

    abstract protected function assertGotenbergFormDataFile(string $name, string $contentType, string $path): void;

    /**
     * @dataProvider provideTestToAddAssets
     */
    public function testToAddAssets(string|\Stringable $asset, string $expectedFilename, string $expectedContentType, string $expectedPath): void
    {
        $this->getDefaultBuilder()
            ->assets($asset)
            ->generate()
        ;

        $this->assertContentFile($expectedFilename, $expectedContentType);
    }

    public static function provideTestToAddAssets(): \Generator
    {
        yield 'string' => ['assets/logo.png', 'logo.png', 'image/png', self::FIXTURE_DIR.'/assets/logo.png'];
        yield 'SplFileInfo' => [new \SplFileInfo('assets/logo.png'), 'logo.png', 'image/png', 'assets/logo.png'];

        $stringable = new class implements \Stringable {
            public function __toString(): string
            {
                return 'assets/logo.png';
            }
        };
        yield 'Stringable' => [$stringable, 'logo.png', 'image/png', 'assets/logo.png'];
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
