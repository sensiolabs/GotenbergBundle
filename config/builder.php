<?php

use Sensiolabs\GotenbergBundle\Builder\AbstractBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->defaults()
        ->tag('monolog.logger', ['channel' => 'sensiolabs_gotenberg'])
    ;

    $services->set('.sensiolabs_gotenberg.abstract_builder', AbstractBuilder::class)
        ->abstract()
        ->args([
            service('sensiolabs_gotenberg.client'),
            service('sensiolabs_gotenberg.asset.base_dir_formatter'),
        ])
    ;
};
