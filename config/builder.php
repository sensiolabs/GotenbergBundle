<?php

use Sensiolabs\GotenbergBundle\Builder\AbstractBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service_locator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_locator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->defaults()
        ->tag('monolog.logger', ['channel' => 'sensiolabs_gotenberg'])
    ;

    $services->set('.sensiolabs_gotenberg.abstract_builder', AbstractBuilder::class)
        ->abstract()
        ->args([
            service('sensiolabs_gotenberg.client'),
            service_locator([
                'asset_base_dir_formatter' => service('.sensiolabs_gotenberg.asset.base_dir_formatter'),
                'logger' => service('logger')->nullOnInvalid(),
                'request_stack' => service('request_stack')->nullOnInvalid(),
                'router' => service('router')->nullOnInvalid(),
                'twig' => service('twig')->nullOnInvalid(),
            ]),
            tagged_locator('sensiolabs_gotenberg.payload_resolver'),
        ])
    ;
};
