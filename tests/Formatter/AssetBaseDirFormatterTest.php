<?php

namespace Sensiolabs\GotenbergBundle\Tests\Formatter;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;

final class AssetBaseDirFormatterTest extends TestCase
{
    private const PROJECT_DIR = __DIR__.'/../';
    private const ASSETS_DIR = __DIR__.'/../Fixtures/assets';

    /**
     * @return iterable<string, array<int, list<string>|string>>
     */
    public static function generateBaseDirectoryAndPath(): iterable
    {
        yield 'absolute path and absolute base dir' => [self::ASSETS_DIR.'/file.md', [self::ASSETS_DIR], self::ASSETS_DIR.'/file.md'];
        yield 'absolute path and relative base dir' => [self::ASSETS_DIR.'/file.md', ['assets'], self::ASSETS_DIR.'/file.md'];
        yield 'relative path and relative base dir' => ['file.md', ['Fixtures/assets'], \dirname(self::PROJECT_DIR, 2).'/Fixtures/assets/file.md'];
        yield 'relative path and absolute base dir' => ['office/document.odt', [self::ASSETS_DIR], \dirname(self::PROJECT_DIR, 2).'/Fixtures/assets/office/document.odt'];
        yield 'relative path and relative base dir with end slash' => ['document.odt', ['Fixtures/assets/office/'], \dirname(self::PROJECT_DIR, 2).'/Fixtures/assets/office/document.odt'];
        yield 'URL path and absolute base dir' => ['https://sensiolabs.com/assets/images/sensiolabs/sensiolabs.fr-OAnPSf0.png', [self::ASSETS_DIR], 'https://sensiolabs.com/assets/images/sensiolabs/sensiolabs.fr-OAnPSf0.png'];
        yield 'URL path and relative base dir' => ['https://sensiolabs.com/assets/images/sensiolabs/sensiolabs.fr-OAnPSf0.png', ['assets'], 'https://sensiolabs.com/assets/images/sensiolabs/sensiolabs.fr-OAnPSf0.png'];
    }

    /**
     * @param string[] $baseDirectories
     */
    #[DataProvider('generateBaseDirectoryAndPath')]
    #[TestDox('Resolve path when "$_dataName"')]
    public function testResolvePathCorrectly(string $path, array $baseDirectories, string $expectedResult): void
    {
        $assetBaseDirFormatter = new AssetBaseDirFormatter(self::PROJECT_DIR, $baseDirectories);
        $resolvedPath = $assetBaseDirFormatter->resolve($path);
        self::assertSame($expectedResult, $resolvedPath);
    }
}
