<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Sensiolabs\GotenbergBundle\Twig\GotenbergAssetExtension;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Mime\Part\DataPart;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

abstract class AbstractBuilderTestCase extends TestCase
{
    protected const FIXTURE_DIR = __DIR__.'/../Fixtures';

    protected static Environment $twig;

    protected static AssetBaseDirFormatter $assetBaseDirFormatter;

    /**
     * @var MockObject&GotenbergClientInterface
     */
    protected GotenbergClientInterface $gotenbergClient;

    public static function setUpBeforeClass(): void
    {
        self::$twig = new Environment(new FilesystemLoader(self::FIXTURE_DIR), [
            'strict_variables' => true,
        ]);
        self::$twig->addExtension(new GotenbergAssetExtension());
        self::$assetBaseDirFormatter = new AssetBaseDirFormatter(new Filesystem(), self::FIXTURE_DIR, self::FIXTURE_DIR);
    }

    protected function setUp(): void
    {
        $this->gotenbergClient = $this->createMock(GotenbergClientInterface::class);
    }

    /**
     * @param array<mixed> $data
     */
    protected function assertFile(array $data, string $filename, string $contentType = 'text/html', string|null $expectedContent = null): void
    {
        self::assertArrayHasKey('files', $data);

        $file = $data['files'];

        self::assertInstanceOf(DataPart::class, $file);
        self::assertSame($filename, $file->getFilename());
        self::assertSame($contentType, $file->getContentType());

        if (null !== $expectedContent) {
            self::assertSame($expectedContent, $file->getBody());
        }

        \iterator_to_array($file->bodyToIterable()); // Check if path is correct
    }
}
