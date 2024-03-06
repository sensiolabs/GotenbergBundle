<?php

use Sensiolabs\GotenbergBundle\Client\GotenbergClient;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Sensiolabs\GotenbergBundle\Pdf\Gotenberg;
use Sensiolabs\GotenbergBundle\Pdf\GotenbergInterface;
use Sensiolabs\GotenbergBundle\Twig\GotenbergAssetExtension;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Filesystem\Filesystem;
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
            service('sensiolabs_gotenberg.asset.base_dir_formatter'),
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

    $services->set('sensiolabs_gotenberg.asset.base_dir_formatter', AssetBaseDirFormatter::class)
        ->args([
            service(Filesystem::class),
            param('kernel.project_dir'),
            abstract_arg('base_directory to assets'),
        ])
        ->alias(AssetBaseDirFormatter::class, 'sensiolabs_gotenberg.asset.base_dir_formatter')
    ;

    $services->set('sensiolabs_gotenberg.twig.asset_extension', GotenbergAssetExtension::class)
        ->tag('twig.extension')
    ;
};
