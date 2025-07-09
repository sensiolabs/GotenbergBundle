<?php

use Sensiolabs\GotenbergBundle\DataCollector\GotenbergDataCollector;
use Sensiolabs\GotenbergBundle\Debug\Client\TraceableGotenbergClient;
use Sensiolabs\GotenbergBundle\Debug\TraceableGotenbergPdf;
use Sensiolabs\GotenbergBundle\Debug\TraceableGotenbergScreenshot;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Reference;
use function Symfony\Component\DependencyInjection\Loader\Configurator\abstract_arg;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_locator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set('sensiolabs_gotenberg.traceable_pdf', TraceableGotenbergPdf::class)
        ->decorate('sensiolabs_gotenberg.pdf')
        ->args([
            new Reference('.inner'),
        ])
    ;

    $services->set('sensiolabs_gotenberg.traceable_screenshot', TraceableGotenbergScreenshot::class)
        ->decorate('sensiolabs_gotenberg.screenshot')
        ->args([
            new Reference('.inner'),
        ])
    ;

    $services->set('sensiolabs_gotenberg.traceable_client', TraceableGotenbergClient::class)
        ->decorate('sensiolabs_gotenberg.client')
        ->args([
            new Reference('.inner'),
        ])
    ;

    $services->set('sensiolabs_gotenberg.data_collector', GotenbergDataCollector::class)
        ->args([
            service('sensiolabs_gotenberg'),
            service('sensiolabs_gotenberg.pdf'),
            service('sensiolabs_gotenberg.screenshot'),
            tagged_locator('sensiolabs_gotenberg.builder'),
            service('sensiolabs_gotenberg.client'),
            abstract_arg('All default options will be set through the configuration.'),
        ])
        ->tag('data_collector', ['template' => '@SensiolabsGotenberg/Collector/sensiolabs_gotenberg.html.twig', 'id' => 'sensiolabs_gotenberg'])
    ;
};
