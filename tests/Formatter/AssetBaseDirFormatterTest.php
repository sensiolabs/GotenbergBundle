<?php

namespace Formatter;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
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
        yield 'absolute path and absolute base dir' => [__DIR__.'/../Fixtures/assets/file.md', __DIR__.'/../Fixtures/assets', __DIR__.'/../Fixtures/assets/file.md'];
        yield 'absolute path and relative base dir' => [__DIR__.'/../Fixtures/assets/logo.png', '/assets', __DIR__.'/../Fixtures/assets/logo.png'];
        yield 'relative path and relative base dir' => ['document.odt', 'assets/office', __DIR__.'/../Fixtures/assets/office/document.odt'];
        yield 'relative path and relative base dir with end slash' => ['document.odt', 'assets/office/', __DIR__.'/../Fixtures/assets/office/document.odt'];
        yield 'relative path and absolute base dir' => ['office/document_1.docx', __DIR__.'/../Fixtures/assets', __DIR__.'/../Fixtures/assets/office/document_1.docx'];
    }

    #[DataProvider('generateBaseDirectoryAndPath')]
    #[TestDox('Resolve path when "$_dataName"')]
    public function testResolvePathCorrectly(string $path, string $baseDirectory, string $expectedResult): void
    {
        $filesystem = new Filesystem();
        $assetBaseDirFormatter = new AssetBaseDirFormatter($filesystem, __DIR__.'/../Fixtures', $baseDirectory);
        $resolvedPath = $assetBaseDirFormatter->resolve($path);
        self::assertSame($expectedResult, $resolvedPath);
    }
}
