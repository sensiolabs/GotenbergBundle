<?php

use Sensiolabs\GotenbergBundle\DataCollector\GotenbergDataCollector;
use Sensiolabs\GotenbergBundle\Debug\TraceableGotenbergPdf;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Reference;
use function Symfony\Component\DependencyInjection\Loader\Configurator\abstract_arg;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_locator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set('sensiolabs_gotenberg.traceable', TraceableGotenbergPdf::class)
        ->decorate('sensiolabs_gotenberg.pdf')
        ->args([
            new Reference('.inner'),
        ])
    ;

    $services->set('sensiolabs_gotenberg.data_collector', GotenbergDataCollector::class)
        ->args([
            service('sensiolabs_gotenberg.pdf'),
            tagged_locator('sensiolabs_gotenberg.pdf_builder'),
            abstract_arg('All default options will be set through the configuration.'),
        ])
        ->tag('data_collector', ['template' => '@SensiolabsGotenberg/Collector/sensiolabs_gotenberg.html.twig', 'id' => 'sensiolabs_gotenberg'])
    ;
};
