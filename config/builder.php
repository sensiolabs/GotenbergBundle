<?php

use Sensiolabs\GotenbergBundle\Builder\AbstractBuilder;
use Sensiolabs\GotenbergBundle\Configurator\BuilderConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\abstract_arg;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service_locator;

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
                'webhook_configuration_registry' => service('.sensiolabs_gotenberg.webhook_configuration_registry'),
                'logger' => service('logger')->nullOnInvalid(),
                'request_stack' => service('request_stack')->nullOnInvalid(),
                'router' => service('router.default')->nullOnInvalid(),
                'twig' => service('twig')->nullOnInvalid(),
            ]),
        ])
    ;

    $services->set('sensiolabs_gotenberg.builder_configurator', BuilderConfigurator::class)
        ->args([
            abstract_arg('Mapping of methods per builder for each configuration key'),
            abstract_arg('Mapping of values per builder for each configuration key'),
        ])
    ;
};
