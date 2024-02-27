<?php

namespace Formatter;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Symfony\Component\Filesystem\Filesystem;

#[CoversClass(AssetBaseDirFormatter::class)]
final class AssetBaseDirFormatterTest extends TestCase
{
    /**
     * @return iterable<string, array<int, string>>
     */
    public static function generateBaseDirectoryAndPath(): iterable
    {
        yield 'absolute path and relative base dir' => [__DIR__.'/../Fixtures/assets/logo.png', '/assets'];
        yield 'relative path and absolute base dir' => ['logo.png', __DIR__.'/../Fixtures/assets'];
        yield 'relative path and relative base dir' => ['logo.png', 'assets'];
        yield 'relative path and relative base dir with end slash' => ['logo.png', 'assets/'];
    }

    #[DataProvider('generateBaseDirectoryAndPath')]
    public function testResolveWithAbsolutePath(string $path, string $baseDirectory): void
    {
        $filesystem = new Filesystem();
        $assetBaseDirFormatter = new AssetBaseDirFormatter($filesystem, __DIR__.'/../Fixtures', $baseDirectory);
        $resolvedPath = $assetBaseDirFormatter->resolve($path);
        self::assertSame(__DIR__.'/../Fixtures/assets/logo.png', $resolvedPath);
    }
}
