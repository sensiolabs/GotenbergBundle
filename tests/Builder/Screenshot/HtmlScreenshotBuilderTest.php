<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Screenshot;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\HtmlScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Exception\JsonEncodingException;
use Sensiolabs\GotenbergBundle\Exception\ScreenshotPartRenderingException;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Sensiolabs\GotenbergBundle\Tests\Builder\AbstractBuilderTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Mime\Part\DataPart;

#[CoversClass(HtmlScreenshotBuilder::class)]
#[UsesClass(AssetBaseDirFormatter::class)]
#[UsesClass(Filesystem::class)]
final class HtmlScreenshotBuilderTest extends AbstractBuilderTestCase
{
    public function testWithConfigurations(): void
    {
        $client = $this->createMock(GotenbergClientInterface::class);
        $assetBaseDirFormatter = new AssetBaseDirFormatter(new Filesystem(), self::FIXTURE_DIR, self::FIXTURE_DIR);

        $builder = new HtmlScreenshotBuilder($client, $assetBaseDirFormatter);
        $builder->contentFile('content.html');
        $builder->setConfigurations(self::getUserScreenshotConfig());

        $multipartFormData = $builder->getMultipartFormData();

        self::assertCount(16, $multipartFormData);

        self::assertIsArray($multipartFormData[0]);
        self::assertCount(1, $multipartFormData[0]);
        self::assertArrayHasKey('files', $multipartFormData[0]);
        self::assertInstanceOf(DataPart::class, $multipartFormData[0]['files']);
        self::assertSame('index.html', $multipartFormData[0]['files']->getFilename());

        self::assertSame(['width' => '1000'], $multipartFormData[1]);
        self::assertSame(['height' => '500'], $multipartFormData[2]);
        self::assertSame(['clip' => 'true'], $multipartFormData[3]);
        self::assertSame(['format' => 'jpeg'], $multipartFormData[4]);
        self::assertSame(['quality' => '75'], $multipartFormData[5]);
        self::assertSame(['omitBackground' => 'false'], $multipartFormData[6]);
        self::assertSame(['optimizeForSpeed' => 'true'], $multipartFormData[7]);
        self::assertSame(['waitDelay' => '5s'], $multipartFormData[8]);
        self::assertSame(['waitForExpression' => 'window.globalVar === "ready"'], $multipartFormData[9]);
        self::assertSame(['emulatedMediaType' => 'screen'], $multipartFormData[10]);
        self::assertSame(['cookies' => '[{"name":"cook_me","value":"sensio","domain":"sensiolabs.com","secure":true,"httpOnly":true,"sameSite":"Lax"},{"name":"yummy_cookie","value":"choco","domain":"example.com"}]'], $multipartFormData[11]);
        self::assertSame(['extraHttpHeaders' => '{"MyHeader":"MyValue","User-Agent":"MyValue"}'], $multipartFormData[12]);
        self::assertSame(['failOnHttpStatusCodes' => '[401,403]'], $multipartFormData[13]);
        self::assertSame(['failOnConsoleExceptions' => 'false'], $multipartFormData[14]);
        self::assertSame(['skipNetworkIdleEvent' => 'true'], $multipartFormData[15]);
    }

    public function testWithTemplate(): void
    {
        $client = $this->createMock(GotenbergClientInterface::class);
        $assetBaseDirFormatter = new AssetBaseDirFormatter(new Filesystem(), self::FIXTURE_DIR, self::FIXTURE_DIR);

        $builder = new HtmlScreenshotBuilder($client, $assetBaseDirFormatter, self::$twig);
        $builder->content('content.html.twig');

        $multipartFormData = $builder->getMultipartFormData();

        self::assertCount(1, $multipartFormData);
        self::assertArrayHasKey(0, $multipartFormData);
        self::assertIsArray($multipartFormData[0]);
        self::assertArrayHasKey('files', $multipartFormData[0]);
        self::assertInstanceOf(DataPart::class, $multipartFormData[0]['files']);
        self::assertSame('text/html', $multipartFormData[0]['files']->getContentType());
    }

    public function testWithAssets(): void
    {
        $client = $this->createMock(GotenbergClientInterface::class);
        $assetBaseDirFormatter = new AssetBaseDirFormatter(new Filesystem(), self::FIXTURE_DIR, self::FIXTURE_DIR);

        $builder = new HtmlScreenshotBuilder($client, $assetBaseDirFormatter);
        $builder->contentFile('content.html');
        $builder->assets('assets/logo.png');

        $multipartFormData = $builder->getMultipartFormData();

        self::assertCount(2, $multipartFormData);

        self::assertArrayHasKey(1, $multipartFormData);
        self::assertIsArray($multipartFormData[1]);
        self::assertArrayHasKey('files', $multipartFormData[1]);
        self::assertInstanceOf(DataPart::class, $multipartFormData[1]['files']);
        self::assertSame('image/png', $multipartFormData[1]['files']->getContentType());
    }

    public function testWithHeader(): void
    {
        $client = $this->createMock(GotenbergClientInterface::class);
        $assetBaseDirFormatter = new AssetBaseDirFormatter(new Filesystem(), self::FIXTURE_DIR, self::FIXTURE_DIR);

        $builder = new HtmlScreenshotBuilder($client, $assetBaseDirFormatter);
        $builder->headerFile('header.html');
        $builder->contentFile('content.html');

        $multipartFormData = $builder->getMultipartFormData();

        self::assertCount(2, $multipartFormData);

        self::assertArrayHasKey(1, $multipartFormData);
        self::assertIsArray($multipartFormData[1]);
        self::assertArrayHasKey('files', $multipartFormData[1]);
        self::assertInstanceOf(DataPart::class, $multipartFormData[1]['files']);
        self::assertSame('text/html', $multipartFormData[1]['files']->getContentType());
    }

    public function testInvalidTwigTemplate(): void
    {
        $this->expectException(ScreenshotPartRenderingException::class);
        $this->expectExceptionMessage('Could not render template "invalid.html.twig" into PDF part "index.html".');

        $client = $this->createMock(GotenbergClientInterface::class);
        $assetBaseDirFormatter = new AssetBaseDirFormatter(new Filesystem(), self::FIXTURE_DIR, self::FIXTURE_DIR);

        $builder = new HtmlScreenshotBuilder($client, $assetBaseDirFormatter, self::$twig);

        $builder->content('invalid.html.twig');
    }

    public function testInvalidExtraHttpHeaders(): void
    {
        $this->expectException(JsonEncodingException::class);
        $this->expectExceptionMessage('Could not encode property "extraHttpHeaders" into JSON');

        $client = $this->createMock(GotenbergClientInterface::class);
        $assetBaseDirFormatter = new AssetBaseDirFormatter(new Filesystem(), self::FIXTURE_DIR, self::FIXTURE_DIR);

        $builder = new HtmlScreenshotBuilder($client, $assetBaseDirFormatter);
        $builder->contentFile('content.html');
        // @phpstan-ignore-next-line
        $builder->extraHttpHeaders([
            'invalid' => tmpfile(),
        ]);

        $builder->getMultipartFormData();
    }

    /**
     * @return array<string, mixed>
     */
    private static function getUserScreenshotConfig(): array
    {
        return [
            'width' => 1000,
            'height' => 500,
            'clip' => true,
            'format' => 'jpeg',
            'quality' => 75,
            'omit_background' => false,
            'optimize_for_speed' => true,
            'wait_delay' => '5s',
            'wait_for_expression' => 'window.globalVar === "ready"',
            'emulated_media_type' => 'screen',
            'cookies' => [
                [
                    'name' => 'cook_me',
                    'value' => 'sensio',
                    'domain' => 'sensiolabs.com',
                    'secure' => true,
                    'httpOnly' => true,
                    'sameSite' => 'Lax',
                ],
                [
                    'name' => 'yummy_cookie',
                    'value' => 'choco',
                    'domain' => 'example.com',
                ],
            ],
            'extra_http_headers' => ['MyHeader' => 'MyValue', 'User-Agent' => 'MyValue'],
            'fail_on_http_status_codes' => [401, 403],
            'fail_on_console_exceptions' => false,
            'skip_network_idle_event' => true,
        ];
    }
}
