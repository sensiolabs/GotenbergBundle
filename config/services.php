<?php

use Sensiolabs\GotenbergBundle\Client\GotenbergClient;
use Sensiolabs\GotenbergBundle\Pdf\Gotenberg;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\abstract_arg;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

return function(ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set('sensiolabs_gotenberg', Gotenberg::class)
        ->args([
            service('sensiolabs_gotenberg.client'),
            service('twig'),
            abstract_arg('user configuration options'),
            param('kernel.project_dir'),
        ])
        ->public();
    $services->alias(Gotenberg::class, 'sensiolabs_gotenberg')
        ->private();

    $services->set('sensiolabs_gotenberg.client', GotenbergClient::class)
        ->args([
            abstract_arg('base_uri to gotenberg API'),
            service('Symfony\Contracts\HttpClient\HttpClientInterface'),
        ])
        ->public();
    $services->alias(GotenbergClient::class, 'sensiolabs_gotenberg.client')
        ->private();
};
