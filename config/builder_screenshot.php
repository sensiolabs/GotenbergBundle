<?php

// use Sensiolabs\GotenbergBundle\Builder\Screenshot\HtmlScreenshotBuilder;
// use Sensiolabs\GotenbergBundle\Builder\Screenshot\MarkdownScreenshotBuilder;
// use Sensiolabs\GotenbergBundle\Builder\Screenshot\UrlScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\HtmlScreenshotBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\abstract_arg;
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
        ->tag('sensiolabs_gotenberg.builder')
        ->tag('sensiolabs_gotenberg.screenshot_builder')
    ;

    //    $services->set('.sensiolabs_gotenberg.screenshot_builder.html', HtmlScreenshotBuilder::class)
    //        ->share(false)
    //        ->args([
    //            service('sensiolabs_gotenberg.client'),
    //            service('.sensiolabs_gotenberg.asset.base_dir_formatter'),
    //            service('.sensiolabs_gotenberg.webhook_configuration_registry'),
    //            service('request_stack'),
    //            service('twig')->nullOnInvalid(),
    //        ])
    //        ->call('setLogger', [service('logger')->nullOnInvalid()])
    //        ->tag('sensiolabs_gotenberg.screenshot_builder')
    //    ;
    //
    //    $services->set('.sensiolabs_gotenberg.screenshot_builder.url', UrlScreenshotBuilder::class)
    //        ->share(false)
    //        ->args([
    //            service('sensiolabs_gotenberg.client'),
    //            service('.sensiolabs_gotenberg.asset.base_dir_formatter'),
    //            service('.sensiolabs_gotenberg.webhook_configuration_registry'),
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
    //            service('.sensiolabs_gotenberg.asset.base_dir_formatter'),
    //            service('.sensiolabs_gotenberg.webhook_configuration_registry'),
    //            service('request_stack'),
    //            service('twig')->nullOnInvalid(),
    //        ])
    //        ->call('setLogger', [service('logger')->nullOnInvalid()])
    //        ->tag('sensiolabs_gotenberg.screenshot_builder')
    //    ;
};
