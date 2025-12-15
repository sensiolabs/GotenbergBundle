<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->alias('alias.sensiolabs_gotenberg.webhook_configuration_registry', '.sensiolabs_gotenberg.webhook_configuration_registry')
        ->public()
    ;
};
