<?php

namespace Sensiolabs\GotenbergBundle\Tests\Formatter;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;

final class AssetBaseDirFormatterTest extends TestCase
{
    /**
     * @return iterable<string, array<int, string|bool>>
     */
    public static function generateBaseDirectoryAndPath(): iterable
    {
        yield 'absolute path and absolute base dir' => ['/mock/foo/file.md', '/mock/bar', false, '/mock/foo/file.md'];
        yield 'absolute path and relative base dir' => ['/mock/foo/logo.png', '/bar', false, '/mock/foo/logo.png'];
        yield 'relative path and relative base dir' => ['document.odt', 'bar/baz', false, '/mock/bar/baz/document.odt'];
        yield 'relative path and absolute base dir' => ['foo/document.odt', '/mock/bar/baz', false, '/mock/bar/baz/foo/document.odt'];
        yield 'relative path and relative base dir with end slash' => ['document.odt', 'bar/baz/', false, '/mock/bar/baz/document.odt'];

        yield 'versioned absolute path and absolute base dir' => ['/mock/foo/file.md', '/mock/bar', true, '/mock/foo/file.md'];
        yield 'versioned absolute path and relative base dir' => ['/mock/foo/logo.png', '/bar', true, '/mock/foo/logo.png'];
        yield 'versioned relative path and relative base dir' => ['document.odt', 'bar/baz', true, '/mock/public/document.odt'];
        yield 'versioned relative path and absolute base dir' => ['foo/document.odt', '/mock/bar/baz', true, '/mock/public/foo/document.odt'];
        yield 'versioned relative path and relative base dir with end slash' => ['document.odt', 'bar/baz/', true, '/mock/public/document.odt'];
    }

    #[DataProvider('generateBaseDirectoryAndPath')]
    #[TestDox('Resolve path when "$_dataName"')]
    public function testResolvePathCorrectly(string $path, string $baseDirectory, bool $versioned, string $expectedResult): void
    {
        $assetBaseDirFormatter = new AssetBaseDirFormatter('/mock', $baseDirectory);
        $resolvedPath = $assetBaseDirFormatter->resolve($path, $versioned);
        self::assertSame($expectedResult, $resolvedPath);
    }
}
