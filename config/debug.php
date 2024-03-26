<?php

use Sensiolabs\GotenbergBundle\DataCollector\GotenbergDataCollector;
use Sensiolabs\GotenbergBundle\Debug\TraceableGotenberg;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Reference;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set('sensiolabs_gotenberg.traceable', TraceableGotenberg::class)
        ->decorate('sensiolabs_gotenberg')
        ->args([
            '$inner' => new Reference('.inner'),
        ])
    ;

    $services->set('sensiolabs_gotenberg.data_collector', GotenbergDataCollector::class)
        ->args([
            '$traceableGotenberg' => service('sensiolabs_gotenberg'),
        ])
        ->tag('data_collector', ['template' => '@SensiolabsGotenberg/WebProfiler/Collector/sensiolabs_gotenberg.html.twig', 'id' => 'sensiolabs_gotenberg'])
    ;
};
