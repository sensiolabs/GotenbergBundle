<?php

namespace Sensiolabs\GotenbergBundle\Tests\DependencyInjection;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\DependencyInjection\WebhookConfigurationRegistry;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;

/**
 * @phpstan-type WebhookDefinition array{url?: string, route?: array{0: string, 1: array<string|int, mixed>}, webhook?: string}
 */
#[CoversClass(WebhookConfigurationRegistry::class)]
final class WebhookConfigurationRegistryTest extends TestCase
{
    public function testGetUndefinedConfiguration(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Webhook configuration "undefined" not found.');

        $registry = new WebhookConfigurationRegistry($this->createMock(UrlGeneratorInterface::class), null);
        $registry->get('undefined');
    }

    public function testAddConfigurationUsingCustomContext(): void
    {
        $requestContext = $this->createMock(RequestContext::class);
        $urlGenerator = $this->getUrlGenerator($requestContext);
        $registry = new WebhookConfigurationRegistry($urlGenerator, $requestContext);
        $registry->add('test', ['success' => ['url' => 'http://example.com/success']]);
    }

    public function testOverrideConfiguration(): void
    {
        $registry = new WebhookConfigurationRegistry($this->createMock(UrlGeneratorInterface::class), null);
        $registry->add('test', ['success' => ['url' => 'http://example.com/success']]);
        $this->assertSame(['success' => 'http://example.com/success', 'error' => 'http://example.com/success'], $registry->get('test'));
        $registry->add('test', ['success' => ['url' => 'http://example.com/override']]);
        $this->assertSame(['success' => 'http://example.com/override', 'error' => 'http://example.com/override'], $registry->get('test'));
    }

    /**
     * @return \Generator<string, array{0: array{success: WebhookDefinition, error?: WebhookDefinition}, 1: array{success: string, error: string}}>
     */
    public static function configurationProvider(): \Generator
    {
        yield 'full definition with urls' => [
            ['success' => ['url' => 'http://example.com/success'], 'error' => ['url' => 'http://example.com/error']],
            ['success' => 'http://example.com/success', 'error' => 'http://example.com/error'],
        ];
        yield 'full definition with routes' => [
            ['success' => ['route' => ['test_route_success', ['param' => 'value']]], 'error' => ['route' => ['test_route_error', ['param' => 'value']]]],
            ['success' => 'http://localhost/test_route?param=value', 'error' => 'http://localhost/test_route?param=value'],
        ];
        yield 'full definition with webhook' => [
            ['success' => ['webhook' => 'my_success_webhook'], 'error' => ['webhook' => 'my_error_webhook']],
            ['success' => 'http://localhost/webhook/success', 'error' => 'http://localhost/webhook/error'],
        ];
        yield 'partial definition with urls' => [
            ['success' => ['url' => 'http://example.com/success']],
            ['success' => 'http://example.com/success', 'error' => 'http://example.com/success'],
        ];
        yield 'partial definition with routes' => [
            ['success' => ['route' => ['test_route_success', ['param' => 'value']]],
                'error' => ['route' => ['test_route_error', ['param' => 'value']]],
            ],
            ['success' => 'http://localhost/test_route?param=value', 'error' => 'http://localhost/test_route?param=value'],
        ];
        yield 'partial definition with webhook' => [
            ['success' => ['webhook' => 'my_success_webhook']],
            ['success' => 'http://localhost/webhook/success', 'error' => 'http://localhost/webhook/success'],
        ];
        yield 'mixed definition with url and route' => [
            ['success' => ['url' => 'http://example.com/success'], 'error' => ['route' => ['test_route_error', ['param' => 'value']]],
            ],
            ['success' => 'http://example.com/success', 'error' => 'http://localhost/test_route?param=value'],
        ];
        yield 'mixed definition with url and webhook' => [
            ['success' => ['url' => 'http://example.com/success'], 'error' => ['webhook' => 'my_error_webhook']],
            ['success' => 'http://example.com/success', 'error' => 'http://localhost/webhook/error'],
        ];
        yield 'mixed definition with route and webhook' => [
            ['success' => ['route' => ['test_route_success', ['param' => 'value']]], 'error' => ['webhook' => 'my_error_webhook']],
            ['success' => 'http://localhost/test_route?param=value', 'error' => 'http://localhost/webhook/error'],
        ];
    }

    /**
     * @param array{success: WebhookDefinition, error?: WebhookDefinition} $configuration
     * @param array{success: string, error: string}                        $expectedUrls
     *
     * @throws Exception
     */
    #[DataProvider('configurationProvider')]
    public function testAddConfiguration(array $configuration, array $expectedUrls): void
    {
        $registry = new WebhookConfigurationRegistry($this->getUrlGenerator(), null);
        $registry->add('test', $configuration);

        $this->assertSame($expectedUrls, $registry->get('test'));
    }

    private function getUrlGenerator(RequestContext|null $requestContext = null): UrlGeneratorInterface&MockObject
    {
        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $originalContext = $this->createMock(RequestContext::class);
        $urlGenerator->expects(self::once())->method('getContext')->willReturn($originalContext);
        $urlGenerator->expects(self::exactly(null !== $requestContext ? 2 : 1))
            ->method('setContext')
            ->willReturnCallback(function (RequestContext $context) use ($originalContext, $requestContext): void {
                match ($context) {
                    $requestContext, $originalContext => null,
                    default => self::fail('setContext was called with an unexpected context.'),
                };
            });
        $urlGenerator->method('generate')->willReturnMap([
            ['test_route_success', ['param' => 'value'], UrlGeneratorInterface::ABSOLUTE_URL, 'http://localhost/test_route?param=value'],
            ['test_route_error', ['param' => 'value'], UrlGeneratorInterface::ABSOLUTE_URL, 'http://localhost/test_route?param=value'],
            ['_webhook_controller', ['type' => 'my_success_webhook'], UrlGeneratorInterface::ABSOLUTE_URL, 'http://localhost/webhook/success'],
            ['_webhook_controller', ['type' => 'my_error_webhook'], UrlGeneratorInterface::ABSOLUTE_URL, 'http://localhost/webhook/error'],
        ]);

        return $urlGenerator;
    }
}
