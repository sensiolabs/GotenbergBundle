<?php

use Sensiolabs\GotenbergBundle\Twig\AssetExtension;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set('sensiolabs_gotenberg.twig.asset_extension', AssetExtension::class)
        ->decorate('twig.extension.assets')
        ->args([
            service('.inner'),
            service('assets.packages'),
            param('kernel.project_dir'),
        ])
    ;
};
