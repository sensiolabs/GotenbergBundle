<?php

use Sensiolabs\GotenbergBundle\Client\GotenbergClient;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\EventListener\ProcessBuilderOnControllerResponse;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Sensiolabs\GotenbergBundle\Gotenberg;
use Sensiolabs\GotenbergBundle\GotenbergInterface;
use Sensiolabs\GotenbergBundle\GotenbergPdf;
use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;
use Sensiolabs\GotenbergBundle\GotenbergScreenshot;
use Sensiolabs\GotenbergBundle\GotenbergScreenshotInterface;
use Sensiolabs\GotenbergBundle\Twig\GotenbergExtension;
use Sensiolabs\GotenbergBundle\Twig\GotenbergRuntime;
use Sensiolabs\GotenbergBundle\Webhook\WebhookConfigurationRegistry;
use Sensiolabs\GotenbergBundle\Webhook\WebhookConfigurationRegistryInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\abstract_arg;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service_locator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set('sensiolabs_gotenberg.client', GotenbergClient::class)
        ->args([
            service('sensiolabs_gotenberg.http_client'),
        ])
        ->alias(GotenbergClientInterface::class, 'sensiolabs_gotenberg.client');

    $services->set('.sensiolabs_gotenberg.asset.base_dir_formatter', AssetBaseDirFormatter::class)
        ->args([
            param('kernel.project_dir'),
            abstract_arg('assets_directory to assets'),
        ])
        ->alias(AssetBaseDirFormatter::class, '.sensiolabs_gotenberg.asset.base_dir_formatter')
    ;

    $services->set('sensiolabs_gotenberg.twig.asset_extension', GotenbergExtension::class)
        ->tag('twig.extension')
    ;
    $services->set('sensiolabs_gotenberg.twig.asset_runtime', GotenbergRuntime::class)
        ->tag('twig.runtime')
    ;

    $services->set('sensiolabs_gotenberg.pdf', GotenbergPdf::class)
        ->args([
            abstract_arg('PDF builders services'),
        ])
        ->alias(GotenbergPdfInterface::class, 'sensiolabs_gotenberg.pdf')
    ;

    $services->set('sensiolabs_gotenberg.screenshot', GotenbergScreenshot::class)
        ->args([
            abstract_arg('Screenshot builders services'),
        ])
        ->alias(GotenbergScreenshotInterface::class, 'sensiolabs_gotenberg.screenshot')
    ;

    $services->set('sensiolabs_gotenberg', Gotenberg::class)
        ->args([
            service_locator([
                GotenbergPdfInterface::class => service('sensiolabs_gotenberg.pdf'),
                GotenbergScreenshotInterface::class => service('sensiolabs_gotenberg.screenshot'),
            ]),
            service('sensiolabs_gotenberg.http_client'),
        ])
        ->alias(GotenbergInterface::class, 'sensiolabs_gotenberg')
    ;

    $services->set('sensiolabs_gotenberg.http_kernel.stream_builder', ProcessBuilderOnControllerResponse::class)
        ->tag('kernel.event_listener', ['method' => 'streamBuilder', 'event' => 'kernel.view'])
    ;

    $services->set('.sensiolabs_gotenberg.webhook_configuration_registry', WebhookConfigurationRegistry::class)
        ->args([
            service('router'),
            service('.sensiolabs_gotenberg.request_context')->nullOnInvalid(),
        ])
        ->alias(WebhookConfigurationRegistryInterface::class, '.sensiolabs_gotenberg.webhook_configuration_registry')
    ;
};
