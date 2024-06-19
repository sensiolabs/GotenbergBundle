<?php

use Sensiolabs\GotenbergBundle\Builder\Pdf\HtmlPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\LibreOfficePdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\MarkdownPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\MergePdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\UrlPdfBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->defaults()
        ->tag('monolog.logger', ['channel' => 'sensiolabs_gotenberg'])
    ;

    $services->set('.sensiolabs_gotenberg.pdf_builder.html', HtmlPdfBuilder::class)
        ->share(false)
        ->args([
            service('sensiolabs_gotenberg.client'),
            service('sensiolabs_gotenberg.asset.base_dir_formatter'),
            service('twig')->nullOnInvalid(),
            service('.sensiolabs_gotenberg.webhook_configuration_registry'),
        ])
        ->call('setLogger', [service('logger')->nullOnInvalid()])
        ->tag('sensiolabs_gotenberg.pdf_builder')
    ;

    $services->set('.sensiolabs_gotenberg.pdf_builder.url', UrlPdfBuilder::class)
        ->share(false)
        ->args([
            service('sensiolabs_gotenberg.client'),
            service('sensiolabs_gotenberg.asset.base_dir_formatter'),
            service('twig')->nullOnInvalid(),
            service('router')->nullOnInvalid(),
            service('.sensiolabs_gotenberg.webhook_configuration_registry'),
        ])
        ->call('setLogger', [service('logger')->nullOnInvalid()])
        ->call('setRequestContext', [service('.sensiolabs_gotenberg.request_context')->nullOnInvalid()])
        ->tag('sensiolabs_gotenberg.pdf_builder')
    ;

    $services->set('.sensiolabs_gotenberg.pdf_builder.markdown', MarkdownPdfBuilder::class)
        ->share(false)
        ->args([
            service('sensiolabs_gotenberg.client'),
            service('sensiolabs_gotenberg.asset.base_dir_formatter'),
            service('twig')->nullOnInvalid(),
            service('.sensiolabs_gotenberg.webhook_configuration_registry'),
        ])
        ->call('setLogger', [service('logger')->nullOnInvalid()])
        ->tag('sensiolabs_gotenberg.pdf_builder')
    ;

    $services->set('.sensiolabs_gotenberg.pdf_builder.office', LibreOfficePdfBuilder::class)
        ->share(false)
        ->args([
            service('sensiolabs_gotenberg.client'),
            service('sensiolabs_gotenberg.asset.base_dir_formatter'),
            service('.sensiolabs_gotenberg.webhook_configuration_registry'),
        ])
        ->call('setLogger', [service('logger')->nullOnInvalid()])
        ->tag('sensiolabs_gotenberg.pdf_builder')
    ;

    $services->set('.sensiolabs_gotenberg.pdf_builder.merge', MergePdfBuilder::class)
        ->share(false)
        ->args([
            service('sensiolabs_gotenberg.client'),
            service('sensiolabs_gotenberg.asset.base_dir_formatter'),
        ])
        ->call('setLogger', [service('logger')->nullOnInvalid()])
        ->tag('sensiolabs_gotenberg.pdf_builder')
    ;
};
