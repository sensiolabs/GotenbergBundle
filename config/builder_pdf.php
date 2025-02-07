<?php

use Sensiolabs\GotenbergBundle\Builder\Pdf\HtmlPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\LibreOfficePdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\MergePdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\UrlPdfBuilder;
use Sensiolabs\GotenbergBundle\Configurator\Pdf\HtmlPdfBuilderConfigurator;
use Sensiolabs\GotenbergBundle\Configurator\Pdf\LibreOfficePdfBuilderConfigurator;
use Sensiolabs\GotenbergBundle\Configurator\Pdf\MergePdfBuilderConfigurator;
use Sensiolabs\GotenbergBundle\Configurator\Pdf\UrlPdfBuilderConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\abstract_arg;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->defaults()
        ->tag('monolog.logger', ['channel' => 'sensiolabs_gotenberg'])
    ;

    // HTML
    $services->set('.sensiolabs_gotenberg.pdf_builder_configurator.html', HtmlPdfBuilderConfigurator::class)
        ->args([
            abstract_arg('default configuration'),
        ])
    ;

    $services->set('.sensiolabs_gotenberg.pdf_builder.html', HtmlPdfBuilder::class)
        ->share(false)
        ->parent('.sensiolabs_gotenberg.abstract_builder')
        ->tag('sensiolabs_gotenberg.builder')
        ->tag('sensiolabs_gotenberg.pdf_builder')
        ->configurator([service('.sensiolabs_gotenberg.pdf_builder_configurator.html'), 'setConfigurations'])
    ;

    // URL
    $services->set('.sensiolabs_gotenberg.pdf_builder_configurator.url', UrlPdfBuilderConfigurator::class)
        ->args([
            abstract_arg('default configuration'),
        ])
    ;

    $services->set('.sensiolabs_gotenberg.pdf_builder.url', UrlPdfBuilder::class)
        ->share(false)
        ->parent('.sensiolabs_gotenberg.abstract_builder')
        ->tag('sensiolabs_gotenberg.builder')
        ->tag('sensiolabs_gotenberg.pdf_builder')
        ->configurator([service('.sensiolabs_gotenberg.pdf_builder_configurator.url'), 'setConfigurations'])
    ;

    // Office
    $services->set('.sensiolabs_gotenberg.pdf_builder_configurator.office', LibreOfficePdfBuilderConfigurator::class)
        ->args([
            abstract_arg('default configuration'),
        ])
    ;

    $services->set('.sensiolabs_gotenberg.pdf_builder.office', LibreOfficePdfBuilder::class)
        ->share(false)
        ->parent('.sensiolabs_gotenberg.abstract_builder')
        ->tag('sensiolabs_gotenberg.pdf_builder')
        ->configurator([service('.sensiolabs_gotenberg.pdf_builder_configurator.office'), 'setConfigurations'])
    ;

    // Merge
    $services->set('.sensiolabs_gotenberg.pdf_builder_configurator.merge', MergePdfBuilderConfigurator::class)
        ->args([
            abstract_arg('default configuration'),
        ])
    ;

    $services->set('.sensiolabs_gotenberg.pdf_builder.merge', MergePdfBuilder::class)
        ->share(false)
        ->parent('.sensiolabs_gotenberg.abstract_builder')
        ->tag('sensiolabs_gotenberg.pdf_builder')
        ->configurator([service('.sensiolabs_gotenberg.pdf_builder_configurator.merge'), 'setConfigurations'])
    ;

    //    $services->set('.sensiolabs_gotenberg.pdf_builder.html', HtmlPdfBuilder::class)
    //        ->share(false)
    //        ->args([
    //            service('sensiolabs_gotenberg.client'),
    //            service('.sensiolabs_gotenberg.asset.base_dir_formatter'),
    //            service('.sensiolabs_gotenberg.webhook_configuration_registry'),
    //            service('request_stack'),
    //            service('twig')->nullOnInvalid(),
    //        ])
    //        ->call('setLogger', [service('logger')->nullOnInvalid()])
    //        ->tag('sensiolabs_gotenberg.pdf_builder')
    //    ;
    //
    //    $services->set('.sensiolabs_gotenberg.pdf_builder.url', UrlPdfBuilder::class)
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
    //        ->tag('sensiolabs_gotenberg.pdf_builder')
    //    ;
    //
    //    $services->set('.sensiolabs_gotenberg.pdf_builder.markdown', MarkdownPdfBuilder::class)
    //        ->share(false)
    //        ->args([
    //            service('sensiolabs_gotenberg.client'),
    //            service('.sensiolabs_gotenberg.asset.base_dir_formatter'),
    //            service('.sensiolabs_gotenberg.webhook_configuration_registry'),
    //            service('request_stack'),
    //            service('twig')->nullOnInvalid(),
    //        ])
    //        ->call('setLogger', [service('logger')->nullOnInvalid()])
    //        ->tag('sensiolabs_gotenberg.pdf_builder')
    //    ;
    //
    //    $services->set('.sensiolabs_gotenberg.pdf_builder.office', LibreOfficePdfBuilder::class)
    //        ->share(false)
    //        ->args([
    //            service('sensiolabs_gotenberg.client'),
    //            service('.sensiolabs_gotenberg.asset.base_dir_formatter'),
    //            service('.sensiolabs_gotenberg.webhook_configuration_registry'),
    //        ])
    //        ->call('setLogger', [service('logger')->nullOnInvalid()])
    //        ->tag('sensiolabs_gotenberg.pdf_builder')
    //    ;
    //
    //    $services->set('.sensiolabs_gotenberg.pdf_builder.merge', MergePdfBuilder::class)
    //        ->share(false)
    //        ->args([
    //            service('sensiolabs_gotenberg.client'),
    //            service('.sensiolabs_gotenberg.asset.base_dir_formatter'),
    //            service('.sensiolabs_gotenberg.webhook_configuration_registry'),
    //        ])
    //        ->call('setLogger', [service('logger')->nullOnInvalid()])
    //        ->tag('sensiolabs_gotenberg.pdf_builder')
    //    ;
    //
    //    $services->set('.sensiolabs_gotenberg.pdf_builder.convert', ConvertPdfBuilder::class)
    //        ->share(false)
    //        ->args([
    //            service('sensiolabs_gotenberg.client'),
    //            service('.sensiolabs_gotenberg.asset.base_dir_formatter'),
    //            service('.sensiolabs_gotenberg.webhook_configuration_registry'),
    //        ])
    //        ->call('setLogger', [service('logger')->nullOnInvalid()])
    //        ->tag('sensiolabs_gotenberg.pdf_builder')
    //    ;
    //
    //    $services->set('.sensiolabs_gotenberg.pdf_builder.split', SplitPdfBuilder::class)
    //        ->share(false)
    //        ->args([
    //            service('sensiolabs_gotenberg.client'),
    //            service('.sensiolabs_gotenberg.asset.base_dir_formatter'),
    //            service('.sensiolabs_gotenberg.webhook_configuration_registry'),
    //        ])
    //        ->call('setLogger', [service('logger')->nullOnInvalid()])
    //        ->tag('sensiolabs_gotenberg.pdf_builder')
    //    ;
};
