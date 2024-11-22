<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Screenshot;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\AbstractChromiumScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\AbstractScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Enumeration\EmulatedMediaType;
use Sensiolabs\GotenbergBundle\Tests\Builder\AbstractBuilderTestCase;
use Symfony\Component\HttpFoundation\RequestStack;

#[CoversClass(AbstractChromiumScreenshotBuilder::class)]
#[UsesClass(AbstractScreenshotBuilder::class)]
class AbstractChromiumScreenshotBuilderTest extends AbstractBuilderTestCase
{
    public static function configurationIsCorrectlySetProvider(): \Generator
    {
        yield 'width' => ['width', 10, [
            'width' => '10',
        ]];
        yield 'height' => ['height', 10, [
            'height' => '10',
        ]];
        yield 'clip' => ['clip', false, [
            'clip' => 'false',
        ]];
        yield 'format' => ['format', 'png', [
            'format' => 'png',
        ]];
        yield 'quality' => ['quality', 50, [
            'quality' => 50,
        ]];
        yield 'omit_background' => ['omit_background', false, [
            'omitBackground' => 'false',
        ]];
        yield 'optimize_for_speed' => ['optimize_for_speed', false, [
            'optimizeForSpeed' => 'false',
        ]];
        yield 'wait_delay' => ['wait_delay', '3ms', [
            'waitDelay' => '3ms',
        ]];
        yield 'wait_for_expression' => ['wait_for_expression', "window.status === 'ready'", [
            'waitForExpression' => "window.status === 'ready'",
        ]];
        yield 'emulated_media_type' => ['emulated_media_type', EmulatedMediaType::Screen, [
            'emulatedMediaType' => 'screen',
        ]];
        yield 'cookies' => ['cookies', [['name' => 'MyCookie', 'value' => 'raspberry']], [
            'cookies' => '[{"name":"MyCookie","value":"raspberry"}]',
        ]];
        yield 'user_agent' => ['user_agent', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko)', [
            'userAgent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko)',
        ]];
        yield 'extra_http_headers' => ['extra_http_headers', ['MyHeader' => 'SomeValue'], [
            'extraHttpHeaders' => '{"MyHeader":"SomeValue"}',
        ]];
        yield 'fail_on_http_status_codes' => ['fail_on_http_status_codes', [499, 500], [
            'failOnHttpStatusCodes' => '[499,500]',
        ]];
        yield 'fail_on_resource_http_status_codes' => ['fail_on_resource_http_status_codes', [499, 500], [
            'failOnResourceHttpStatusCodes' => '[499,500]',
        ]];
        yield 'fail_on_resource_loading_failed' => ['fail_on_resource_loading_failed', false, [
            'failOnResourceLoadingFailed' => 'false',
        ]];
        yield 'fail_on_console_exceptions' => ['fail_on_console_exceptions', false, [
            'failOnConsoleExceptions' => 'false',
        ]];
        yield 'skip_network_idle_event' => ['skip_network_idle_event', false, [
            'skipNetworkIdleEvent' => 'false',
        ]];
    }

    /**
     * @param array<mixed> $expected
     */
    #[DataProvider('configurationIsCorrectlySetProvider')]
    #[TestDox('Configuration "$_dataName" is correctly set')]
    public function testConfigurationIsCorrectlySet(string $key, mixed $value, array $expected): void
    {
        $builder = $this->getChromiumScreenshotBuilder();
        $builder->setConfigurations([
            $key => $value,
        ]);

        self::assertEquals($expected, $builder->getMultipartFormData()[0]);
    }

    private function getChromiumScreenshotBuilder(bool $urlGenerator = true, bool $twig = true): AbstractChromiumScreenshotBuilder
    {
        return new class($this->gotenbergClient, self::$assetBaseDirFormatter, $this->webhookConfigurationRegistry, new RequestStack(), $urlGenerator ? self::$urlGenerator : null, $twig ? self::$twig : null) extends AbstractChromiumScreenshotBuilder {
            protected function getEndpoint(): string
            {
                return '/fake/endpoint';
            }
        };
    }
}
