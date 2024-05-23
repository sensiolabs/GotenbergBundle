<?php

use Sensiolabs\GotenbergBundle\Builder\Screenshot\HtmlScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\MarkdownScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\UrlScreenshotBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set('.sensiolabs_gotenberg.screenshot_builder.html', HtmlScreenshotBuilder::class)
        ->share(false)
        ->args([
            service('sensiolabs_gotenberg.client'),
            service('sensiolabs_gotenberg.asset.base_dir_formatter'),
            service('twig')->nullOnInvalid(),
        ])
        ->tag('sensiolabs_gotenberg.screenshot_builder')
    ;

    $services->set('.sensiolabs_gotenberg.screenshot_builder.url', UrlScreenshotBuilder::class)
        ->share(false)
        ->args([
            service('sensiolabs_gotenberg.client'),
            service('sensiolabs_gotenberg.asset.base_dir_formatter'),
            service('twig')->nullOnInvalid(),
            service('router')->nullOnInvalid(),
        ])
        ->tag('sensiolabs_gotenberg.screenshot_builder')
    ;

    $services->set('.sensiolabs_gotenberg.screenshot_builder.markdown', MarkdownScreenshotBuilder::class)
        ->share(false)
        ->args([
            service('sensiolabs_gotenberg.client'),
            service('sensiolabs_gotenberg.asset.base_dir_formatter'),
            service('twig')->nullOnInvalid(),
        ])
        ->tag('sensiolabs_gotenberg.screenshot_builder')
    ;
};
