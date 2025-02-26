<?php

use Sensiolabs\GotenbergBundle\Builder\Pdf\ConvertPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\FlattenPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\HtmlPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\LibreOfficePdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\MarkdownPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\MergePdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\SplitPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\UrlPdfBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->defaults()
        ->tag('monolog.logger', ['channel' => 'sensiolabs_gotenberg'])
    ;

    // HTML
    $services->set('.sensiolabs_gotenberg.pdf_builder.html', HtmlPdfBuilder::class)
        ->share(false)
        ->parent('.sensiolabs_gotenberg.abstract_builder')
        ->tag('sensiolabs_gotenberg.builder')
        ->configurator(service('sensiolabs_gotenberg.builder_configurator'))
    ;

    // URL
    $services->set('.sensiolabs_gotenberg.pdf_builder.url', UrlPdfBuilder::class)
        ->share(false)
        ->parent('.sensiolabs_gotenberg.abstract_builder')
        ->tag('sensiolabs_gotenberg.builder')
        ->configurator(service('sensiolabs_gotenberg.builder_configurator'))
        ->call('setRequestContext', [service('.sensiolabs_gotenberg.request_context')->nullOnInvalid()])
    ;

    // Markdown
    $services->set('.sensiolabs_gotenberg.pdf_builder.markdown', MarkdownPdfBuilder::class)
        ->share(false)
        ->parent('.sensiolabs_gotenberg.abstract_builder')
        ->tag('sensiolabs_gotenberg.builder')
        ->configurator(service('sensiolabs_gotenberg.builder_configurator'))
    ;

    // Office
    $services->set('.sensiolabs_gotenberg.pdf_builder.office', LibreOfficePdfBuilder::class)
        ->share(false)
        ->parent('.sensiolabs_gotenberg.abstract_builder')
        ->tag('sensiolabs_gotenberg.builder')
        ->configurator(service('sensiolabs_gotenberg.builder_configurator'))
    ;

    // Merge
    $services->set('.sensiolabs_gotenberg.pdf_builder.merge', MergePdfBuilder::class)
        ->share(false)
        ->parent('.sensiolabs_gotenberg.abstract_builder')
        ->tag('sensiolabs_gotenberg.builder')
        ->configurator(service('sensiolabs_gotenberg.builder_configurator'))
    ;

    // Convert
    $services->set('.sensiolabs_gotenberg.pdf_builder.convert', ConvertPdfBuilder::class)
        ->share(false)
        ->parent('.sensiolabs_gotenberg.abstract_builder')
        ->tag('sensiolabs_gotenberg.builder')
        ->configurator(service('sensiolabs_gotenberg.builder_configurator'))
    ;

    // Split
    $services->set('.sensiolabs_gotenberg.pdf_builder.split', SplitPdfBuilder::class)
        ->share(false)
        ->parent('.sensiolabs_gotenberg.abstract_builder')
        ->tag('sensiolabs_gotenberg.builder')
        ->configurator(service('sensiolabs_gotenberg.builder_configurator'))
    ;

    // Flatten
    $services->set('.sensiolabs_gotenberg.pdf_builder.flatten', FlattenPdfBuilder::class)
        ->share(false)
        ->parent('.sensiolabs_gotenberg.abstract_builder')
        ->tag('sensiolabs_gotenberg.builder')
        ->configurator(service('sensiolabs_gotenberg.builder_configurator'))
    ;
};
