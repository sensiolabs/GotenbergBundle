<?php

use Sensiolabs\GotenbergBundle\PayloadResolver\AbstractPayloadResolver;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\abstract_arg;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set('.sensiolabs_gotenberg.abstract_payload_resolver', AbstractPayloadResolver::class)
        ->abstract()
        ->args([
            abstract_arg('Gotenberg API version'),
        ])
    ;
};
