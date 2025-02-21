<?php

use Sensiolabs\GotenbergBundle\Builder\Screenshot\HtmlScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\MarkdownScreenshotBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->defaults()
        ->tag('monolog.logger', ['channel' => 'sensiolabs_gotenberg'])
    ;

    // HTML
    $services->set('.sensiolabs_gotenberg.screenshot_builder.html', HtmlScreenshotBuilder::class)
        ->share(false)
        ->parent('.sensiolabs_gotenberg.abstract_builder')
        ->tag('sensiolabs_gotenberg.screenshot_builder')
        ->configurator(service('sensiolabs_gotenberg.builder_configurator'))
    ;

    // Markdown
    $services->set('.sensiolabs_gotenberg.screenshot_builder.markdown', MarkdownScreenshotBuilder::class)
        ->share(false)
        ->parent('.sensiolabs_gotenberg.abstract_builder')
        ->tag('sensiolabs_gotenberg.screenshot_builder')
        ->configurator(service('sensiolabs_gotenberg.builder_configurator'))
    ;
};
