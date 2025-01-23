<?php

use Sensiolabs\GotenbergBundle\PayloadResolver\Screenshot\HtmlScreenshotPayloadResolver;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    // HTML
    $services->set('.sensiolabs_gotenberg.payload_resolver.html_screenshot_builder', HtmlScreenshotPayloadResolver::class)
        ->share(false)
        ->parent('.sensiolabs_gotenberg.abstract_payload_resolver')
        ->tag('sensiolabs_gotenberg.payload_resolver')
    ;
};
