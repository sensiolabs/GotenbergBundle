<?php

declare(strict_types=1);

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\ValueObject\EmbeddedFile;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Symfony\Component\Filesystem\Path;

/**
 * @template T of BuilderInterface
 */
trait EmbedTestCaseTrait
{
    /** @use BehaviorTrait<T> */
    use BehaviorTrait;

    abstract protected function assertGotenbergFormData(string $field, string $expectedValue): void;

    public function testWithEmbeddedFileFromString(): void
    {
        $this->withGotenbergVersion('8.25.0');
        $this->container->set('asset_base_dir_formatter', new AssetBaseDirFormatter(self::FIXTURE_DIR, [self::FIXTURE_DIR]));

        $this->getDefaultBuilder()
            ->filename('testEmbed.pdf')
            ->embedFiles('embed/factur-x.xml')
            ->generate()
        ;

        $this->assertGotenbergFormDataFile('embeds', 'application/xml', self::FIXTURE_DIR.'/embed/factur-x.xml');
    }

    public function testWithEmbeddedFileFromSplFileInfo(): void
    {
        $this->withGotenbergVersion('8.25.0');

        $this->getDefaultBuilder()
            ->filename('testEmbed.pdf')
            ->embedFiles(new \SplFileInfo(Path::canonicalize(self::FIXTURE_DIR.'/embed/factur-x.xml')))
            ->generate()
        ;

        $this->assertGotenbergFormDataFile('embeds', 'application/xml', self::FIXTURE_DIR.'/embed/factur-x.xml');
    }

    public function testWithEmbeddedFileAndRelationship(): void
    {
        $this->withGotenbergVersion('8.31.0');

        $this->getDefaultBuilder()
            ->filename('testEmbed.pdf')
            ->embedFiles(new EmbeddedFile(Path::canonicalize(self::FIXTURE_DIR.'/embed/factur-x.xml'), 'Data'))
            ->generate()
        ;

        $this->assertGotenbergFormDataFile('embeds', 'application/xml', self::FIXTURE_DIR.'/embed/factur-x.xml');
        $this->assertGotenbergFormData('embedsMetadata', '{"factur-x.xml":{"mimeType":"text\/xml","relationship":"Data"}}');
    }
}
