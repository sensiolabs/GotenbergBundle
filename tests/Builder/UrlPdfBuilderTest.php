<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder;

use PHPUnit\Framework\Attributes\CoversClass;
use Sensiolabs\GotenbergBundle\Builder\HtmlPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\UrlPdfBuilder;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Symfony\Component\Filesystem\Filesystem;

#[CoversClass(HtmlPdfBuilder::class)]
final class UrlPdfBuilderTest extends AbstractBuilderTestCase
{
    public function testUrl(): void
    {
        $client = $this->createMock(GotenbergClientInterface::class);
        $filesystem = $this->createMock(Filesystem::class);
        $builder = new UrlPdfBuilder($client, self::FIXTURE_DIR, $filesystem);
        $builder->url('https://google.com');

        $multipartFormData = $builder->getMultipartFormData();

        self::assertCount(1, $multipartFormData);
        self::assertArrayHasKey(0, $multipartFormData);
        self::assertSame(['url' => 'https://google.com'], $multipartFormData[0]);
    }
}
