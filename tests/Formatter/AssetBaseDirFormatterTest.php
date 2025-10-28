<?php

namespace Sensiolabs\GotenbergBundle\Tests\Formatter;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;

final class AssetBaseDirFormatterTest extends TestCase
{
    private const PROJECT_DIR = __DIR__.'/../';

    /**
     * @return iterable<string, array<int, list<string>|string>>
     */
    public static function generateBaseDirectoryAndPath(): iterable
    {
        $projectDir = \dirname(self::PROJECT_DIR, 2);

        yield 'absolute path and absolute base dir' => [$projectDir.'/Fixtures/assets/file.md', [$projectDir.'/Fixtures/assets'], $projectDir.'/Fixtures/assets/file.md'];
        yield 'absolute path and relative base dir' => [$projectDir.'/Fixtures/assets/file.md', ['assets'], $projectDir.'/Fixtures/assets/file.md'];
        yield 'relative path and relative base dir' => ['file.md', ['Fixtures/assets'], $projectDir.'/Fixtures/assets/file.md'];
        yield 'relative path and absolute base dir' => ['office/document.odt', [$projectDir.'/Fixtures/assets'], $projectDir.'/Fixtures/assets/office/document.odt'];
        yield 'relative path and relative base dir with end slash' => ['document.odt', ['Fixtures/assets/office/'], $projectDir.'/Fixtures/assets/office/document.odt'];
        yield 'URL path and absolute base dir' => ['https://sensiolabs.com/assets/images/sensiolabs/sensiolabs.fr-OAnPSf0.png', [$projectDir.'/Fixtures/assets'], 'https://sensiolabs.com/assets/images/sensiolabs/sensiolabs.fr-OAnPSf0.png'];
        yield 'URL path and relative base dir' => ['https://sensiolabs.com/assets/images/sensiolabs/sensiolabs.fr-OAnPSf0.png', ['assets'], 'https://sensiolabs.com/assets/images/sensiolabs/sensiolabs.fr-OAnPSf0.png'];
        yield 'absolute path and two absolute base dir' => [$projectDir.'/Fixtures/assets/office/document.odt', [$projectDir.'/Fixtures/assets', $projectDir.'/Fixtures/assets/office'], $projectDir.'/Fixtures/assets/office/document.odt'];
        yield 'absolute path and two relative base dir' => [$projectDir.'/Fixtures/assets/office/document.odt', ['Fixtures/assets', 'Fixtures/assets/office'], $projectDir.'/Fixtures/assets/office/document.odt'];
        yield 'relative path and two absolute base dir' => ['document.odt', [$projectDir.'/Fixtures/assets', $projectDir.'/Fixtures/assets/office'], $projectDir.'/Fixtures/assets/office/document.odt'];
        yield 'relative path and two relative base dir' => ['document.odt', ['Fixtures/assets', 'Fixtures/assets/office'], $projectDir.'/Fixtures/assets/office/document.odt'];
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
