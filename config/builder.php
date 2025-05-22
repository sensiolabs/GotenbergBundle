<?php

use Psr\Container\ContainerInterface;
use Sensiolabs\GotenbergBundle\Builder\AbstractBuilder;
use Sensiolabs\GotenbergBundle\Configurator\BuilderConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\abstract_arg;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->defaults()
        ->tag('monolog.logger', ['channel' => 'sensiolabs_gotenberg'])
    ;

    $services->set('.sensiolabs_gotenberg.abstract_builder', AbstractBuilder::class)
        ->abstract()
        ->call('setContainer', [service(ContainerInterface::class)])
        ->tag('container.service_subscriber')
    ;

    $services->set('sensiolabs_gotenberg.builder_configurator', BuilderConfigurator::class)
        ->args([
            abstract_arg('Mapping of methods per builder for each configuration key'),
            abstract_arg('Mapping of values per builder for each configuration key'),
        ])
    ;
};
