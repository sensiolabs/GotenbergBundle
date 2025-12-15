<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $container): void {
    $container->extension('framework', [
        'test' => true,
        'http_client' => [
            'scoped_clients' => [
                'gotenberg' => [
                    'base_uri' => 'http://localhost:9000',
                ],
            ],
        ],
    ]);

    $container->extension('sensiolabs_gotenberg', [
        'http_client' => 'gotenberg',
        'webhook' => [
            'default' => [
                'success' => [
                    'url' => 'http://localhost:9000/default_webhook',
                ],
            ],
            'custom' => [
                'extra_http_headers' => [
                    'X-CUSTOM' => 'custom',
                ],
                'success' => [
                    'url' => 'http://localhost:9000/custom_success_webhook',
                    'method' => 'PUT',
                ],
                'error' => [
                    'url' => 'http://localhost:9000/custom_error_webhook',
                    'method' => 'POST',
                ],
            ],
        ],
    ]);
};
