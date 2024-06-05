<?php

use Sensiolabs\GotenbergBundle\Builder\Pdf\HtmlPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\LibreOfficePdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\MarkdownPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\UrlPdfBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set('.sensiolabs_gotenberg.pdf_builder.html', HtmlPdfBuilder::class)
        ->share(false)
        ->args([
            service('sensiolabs_gotenberg.client'),
            service('sensiolabs_gotenberg.asset.base_dir_formatter'),
            service('twig')->nullOnInvalid(),
        ])
        ->tag('sensiolabs_gotenberg.pdf_builder')
    ;

    $services->set('.sensiolabs_gotenberg.pdf_builder.url', UrlPdfBuilder::class)
        ->share(false)
        ->args([
            service('sensiolabs_gotenberg.client'),
            service('sensiolabs_gotenberg.asset.base_dir_formatter'),
            service('twig')->nullOnInvalid(),
            service('router')->nullOnInvalid(),
        ])
        ->call('setRequestContext', [service('.sensiolabs_gotenberg.request_context')->nullOnInvalid()])
        ->tag('sensiolabs_gotenberg.pdf_builder')
    ;

    $services->set('.sensiolabs_gotenberg.pdf_builder.markdown', MarkdownPdfBuilder::class)
        ->share(false)
        ->args([
            service('sensiolabs_gotenberg.client'),
            service('sensiolabs_gotenberg.asset.base_dir_formatter'),
            service('twig')->nullOnInvalid(),
        ])
        ->tag('sensiolabs_gotenberg.pdf_builder')
    ;

    $services->set('.sensiolabs_gotenberg.pdf_builder.office', LibreOfficePdfBuilder::class)
        ->share(false)
        ->args([
            service('sensiolabs_gotenberg.client'),
            service('sensiolabs_gotenberg.asset.base_dir_formatter'),
        ])
        ->tag('sensiolabs_gotenberg.pdf_builder')
    ;
};
