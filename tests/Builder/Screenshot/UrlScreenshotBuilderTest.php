<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Screenshot;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\AbstractChromiumScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\AbstractScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\UrlScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Sensiolabs\GotenbergBundle\Tests\Builder\AbstractBuilderTestCase;
use Symfony\Component\Filesystem\Filesystem;

#[CoversClass(UrlScreenshotBuilder::class)]
#[UsesClass(AbstractScreenshotBuilder::class)]
#[UsesClass(AbstractChromiumScreenshotBuilder::class)]
#[UsesClass(AssetBaseDirFormatter::class)]
#[UsesClass(Filesystem::class)]
final class UrlScreenshotBuilderTest extends AbstractBuilderTestCase
{
    public function testUrl(): void
    {
        $client = $this->createMock(GotenbergClientInterface::class);
        $assetBaseDirFormatter = new AssetBaseDirFormatter(new Filesystem(), self::FIXTURE_DIR, self::FIXTURE_DIR);

        $builder = new UrlScreenshotBuilder($client, $assetBaseDirFormatter);
        $builder->url('https://google.com');

        $multipartFormData = $builder->getMultipartFormData();

        self::assertCount(1, $multipartFormData);
        self::assertArrayHasKey(0, $multipartFormData);
        self::assertSame(['url' => 'https://google.com'], $multipartFormData[0]);
    }
}
