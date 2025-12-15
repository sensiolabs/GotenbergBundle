<?php

namespace Functional;

use Sensiolabs\GotenbergBundle\Tests\Functional\AbstractGotenbergWebTestCase;
use Sensiolabs\GotenbergBundle\Webhook\WebhookConfigurationRegistry;

class WebhookTest extends AbstractGotenbergWebTestCase
{
    public function testBundleWebhook(): void
    {
        static::bootKernel(['test_case' => 'Webhook']);

        /** @var WebhookConfigurationRegistry $registry */
        $registry = static::getContainer()->get('alias.sensiolabs_gotenberg.webhook_configuration_registry');

        self::assertSame(
            [
                'success' => [
                    'url' => 'http://localhost:9000/default_webhook',
                    'method' => null,
                ],
                'error' => [
                    'url' => 'http://localhost:9000/default_webhook',
                    'method' => null,
                ],
            ],
            $registry->get('default'),
        );
        self::assertSame(
            [
                'success' => [
                    'url' => 'http://localhost:9000/custom_success_webhook',
                    'method' => 'PUT',
                ],
                'error' => [
                    'url' => 'http://localhost:9000/custom_error_webhook',
                    'method' => 'POST',
                ],
                'extra_http_headers' => [
                    'X-CUSTOM' => 'custom',
                ],
            ],
            $registry->get('custom'),
        );
    }
}
