<?php

use Sensiolabs\GotenbergBundle\Client\GotenbergClient;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Pdf\Gotenberg;
use Sensiolabs\GotenbergBundle\Pdf\GotenbergInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use function Symfony\Component\DependencyInjection\Loader\Configurator\abstract_arg;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set('sensiolabs_gotenberg', Gotenberg::class)
        ->args([
            service('sensiolabs_gotenberg.client'),
            abstract_arg('chromium configuration options'),
            abstract_arg('office configuration options'),
            param('kernel.project_dir'),
            service('twig')->nullOnInvalid(),
        ])
        ->public()
        ->alias(GotenbergInterface::class, 'sensiolabs_gotenberg');

    $services->set('sensiolabs_gotenberg.client', GotenbergClient::class)
        ->args([
            abstract_arg('base_uri to gotenberg API'),
            service(HttpClientInterface::class),
        ])
        ->public()
        ->alias(GotenbergClientInterface::class, 'sensiolabs_gotenberg.client');
};
