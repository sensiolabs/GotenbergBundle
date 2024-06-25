<?php

use Sensiolabs\GotenbergBundle\RemoteEvent\ErrorPayloadConverter;
use Sensiolabs\GotenbergBundle\RemoteEvent\SuccessPayloadConverter;
use Sensiolabs\GotenbergBundle\Webhook\ErrorWebhookRequestParser;
use Sensiolabs\GotenbergBundle\Webhook\SuccessWebhookRequestParser;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set('sensiolabs_gotenberg.webhook.success_payload_converter', SuccessPayloadConverter::class)
        ->tag('webhook.payload_converter');
    $services->set('sensiolabs_gotenberg.webhook.error_payload_converter', ErrorPayloadConverter::class)
        ->tag('webhook.payload_converter');
    $services->set('sensiolabs_gotenberg.webhook.success_request_parser', SuccessWebhookRequestParser::class)
        ->args([
            service('sensiolabs_gotenberg.webhook.success_payload_converter'),
        ])
        ->tag('webhook.request_parser')
        ->call('setLogger', [service('logger')]);
    $services->set('sensiolabs_gotenberg.webhook.error_request_parser', ErrorWebhookRequestParser::class)
        ->args([
            service('sensiolabs_gotenberg.webhook.error_payload_converter'),
        ])
        ->tag('webhook.request_parser')
        ->call('setLogger', [service('logger')]);
};
