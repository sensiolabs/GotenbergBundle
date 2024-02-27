<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder;

use PHPUnit\Framework\Attributes\CoversClass;
use Sensiolabs\GotenbergBundle\Builder\MarkdownPdfBuilder;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Mime\Part\DataPart;

#[CoversClass(MarkdownPdfBuilder::class)]
final class MarkdownPdfBuilderTest extends AbstractBuilderTestCase
{
    public function testMarkdownFile(): void
    {
        $client = $this->createMock(GotenbergClientInterface::class);
        $filesystem = $this->createMock(Filesystem::class);

        $assetBaseDirFormatter = new AssetBaseDirFormatter($filesystem, __DIR__.self::FIXTURE_DIR, self::FIXTURE_DIR);

        $builder = new MarkdownPdfBuilder($client, $assetBaseDirFormatter);
        $builder
            ->wrapperFile('template.html')
            ->files('assets/file.md')
        ;

        $multipartFormData = $builder->getMultipartFormData();

        self::assertCount(2, $multipartFormData);

        self::assertArrayHasKey(0, $multipartFormData);
        self::assertIsArray($multipartFormData[0]);
        self::assertArrayHasKey('files', $multipartFormData[0]);
        self::assertInstanceOf(DataPart::class, $multipartFormData[0]['files']);
        self::assertSame('index.html', $multipartFormData[0]['files']->getFilename());

        self::assertArrayHasKey(1, $multipartFormData);
        self::assertIsArray($multipartFormData[1]);
        self::assertArrayHasKey('files', $multipartFormData[1]);
        self::assertInstanceOf(DataPart::class, $multipartFormData[1]['files']);
        self::assertSame('file.md', $multipartFormData[1]['files']->getFilename());
        self::assertSame('text/markdown', $multipartFormData[1]['files']->getContentType());
    }
}
