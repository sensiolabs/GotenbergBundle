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
    ]);
};
