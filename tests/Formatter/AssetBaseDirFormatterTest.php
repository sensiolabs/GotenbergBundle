<?php

namespace Sensiolabs\GotenbergBundle\Tests\Formatter;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Symfony\Component\Filesystem\Filesystem;

#[CoversClass(AssetBaseDirFormatter::class)]
final class AssetBaseDirFormatterTest extends TestCase
{
    private const BASE_PATH = '/var/www/project';

    /**
     * @return iterable<string, array<int, string>>
     */
    public static function generateBaseDirectoryAndPath(): iterable
    {
        yield 'absolute path and absolute base dir' => [self::BASE_PATH.'/foo/file.md', self::BASE_PATH.'/bar', self::BASE_PATH.'/foo/file.md'];
        yield 'absolute path and relative base dir' => [self::BASE_PATH.'/foo/logo.png', '/bar', self::BASE_PATH.'/foo/logo.png'];
        yield 'relative path and relative base dir' => ['document.odt', 'bar/baz', self::BASE_PATH.'/bar/baz/document.odt'];
        yield 'relative path and absolute base dir' => ['foo/document.odt', self::BASE_PATH.'/bar/baz', self::BASE_PATH.'/bar/baz/foo/document.odt'];
        yield 'relative path and relative base dir with end slash' => ['document.odt', 'bar/baz/', self::BASE_PATH.'/bar/baz/document.odt'];
    }

    #[DataProvider('generateBaseDirectoryAndPath')]
    #[TestDox('Resolve path when "$_dataName"')]
    public function testResolvePathCorrectly(string $path, string $baseDirectory, string $expectedResult): void
    {
        $filesystem = new Filesystem();
        $assetBaseDirFormatter = new AssetBaseDirFormatter($filesystem, self::BASE_PATH, $baseDirectory);
        $resolvedPath = $assetBaseDirFormatter->resolve($path);
        self::assertSame($expectedResult, $resolvedPath);
    }
}
