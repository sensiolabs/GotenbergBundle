<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Mime\Part\DataPart;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

abstract class AbstractBuilderTestCase extends TestCase
{
    protected const FIXTURE_DIR = __DIR__.'/../Fixtures';

    protected static Environment $twig;

    /**
     * @var MockObject&GotenbergClientInterface
     */
    protected static GotenbergClientInterface $gotenbergClient;
    protected static AssetBaseDirFormatter $assetBaseDirFormatter;

    public static function setUpBeforeClass(): void
    {
        self::$twig = new Environment(new FilesystemLoader(self::FIXTURE_DIR));
        self::$assetBaseDirFormatter = new AssetBaseDirFormatter(new Filesystem(), self::FIXTURE_DIR, self::FIXTURE_DIR);
    }

    protected function setUp(): void
    {
        self::$gotenbergClient = $this->createMock(GotenbergClientInterface::class);
    }

    /**
     * @param array<mixed> $data
     */
    protected function assertFile(array $data, string $filename, string $expectedContent): void
    {
        self::assertArrayHasKey('files', $data);

        $file = $data['files'];

        self::assertInstanceOf(DataPart::class, $file);

        self::assertSame($expectedContent, $file->getBody());
        self::assertSame($filename, $file->getFilename());
    }
}
