<?php

use Sensiolabs\GotenbergBundle\Builder\HtmlScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Configurator\HtmlScreenshotBuilderConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\abstract_arg;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service_locator;


return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->defaults()
        ->tag('monolog.logger', ['channel' => 'sensiolabs_gotenberg'])
    ;

    // HTML
    $services->set('.sensiolabs_gotenberg.screenshot_builder_configurator.html', HtmlScreenshotBuilderConfigurator::class)
        ->args([
            abstract_arg('default configuration'),
        ])
    ;
    $services->set('.sensiolabs_gotenberg.screenshot_builder.html', HtmlScreenshotBuilder::class)
        ->share(false)
        ->parent('.sensiolabs_gotenberg.abstract_builder')
        ->arg(2, service_locator([
            'logger' => service('logger')->nullOnInvalid(),
            'twig' => service('twig')->nullOnInvalid(),
            'request_stack' => service('request_stack')->nullOnInvalid(),
        ]))
        ->configurator(service('.sensiolabs_gotenberg.screenshot_builder_configurator.html'))
        ->tag('sensiolabs_gotenberg.builder')
        ->tag('sensiolabs_gotenberg.screenshot_builder')
    ;

    //    $services->set('.sensiolabs_gotenberg.screenshot_builder.url', UrlScreenshotBuilder::class)
    //        ->share(false)
    //        ->args([
    //            service('sensiolabs_gotenberg.client'),
    //            service('sensiolabs_gotenberg.asset.base_dir_formatter'),
    //            service('request_stack'),
    //            service('twig')->nullOnInvalid(),
    //            service('router')->nullOnInvalid(),
    //        ])
    //        ->call('setLogger', [service('logger')->nullOnInvalid()])
    //        ->call('setRequestContext', [service('.sensiolabs_gotenberg.request_context')->nullOnInvalid()])
    //        ->tag('sensiolabs_gotenberg.screenshot_builder')
    //    ;
    //
    //    $services->set('.sensiolabs_gotenberg.screenshot_builder.markdown', MarkdownScreenshotBuilder::class)
    //        ->share(false)
    //        ->args([
    //            service('sensiolabs_gotenberg.client'),
    //            service('sensiolabs_gotenberg.asset.base_dir_formatter'),
    //            service('request_stack'),
    //            service('twig')->nullOnInvalid(),
    //        ])
    //        ->call('setLogger', [service('logger')->nullOnInvalid()])
    //        ->tag('sensiolabs_gotenberg.screenshot_builder')
    //    ;
};
