<?php

use Sensiolabs\GotenbergBundle\Builder\HtmlBuilder;
use Sensiolabs\GotenbergBundle\Builder\LibreOfficeBuilder;
use Sensiolabs\GotenbergBundle\Builder\MarkdownBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\HtmlPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\LibreOfficePdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\MarkdownPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\UrlPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\UrlBuilder;
use Sensiolabs\GotenbergBundle\Client\GotenbergClient;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Sensiolabs\GotenbergBundle\Gotenberg;
use Sensiolabs\GotenbergBundle\GotenbergInterface;
use Sensiolabs\GotenbergBundle\GotenbergPdf;
use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;
use Sensiolabs\GotenbergBundle\Twig\GotenbergAssetExtension;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Filesystem\Filesystem;
use function Symfony\Component\DependencyInjection\Loader\Configurator\abstract_arg;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service_locator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_locator;

return function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set('sensiolabs_gotenberg.client', GotenbergClient::class)
        ->args([
            abstract_arg('base_uri to gotenberg API'),
            service('sensiolabs_gotenberg.http_client'),
        ])
        ->alias(GotenbergClientInterface::class, 'sensiolabs_gotenberg.client');

    $services->set('sensiolabs_gotenberg.asset.base_dir_formatter', AssetBaseDirFormatter::class)
        ->args([
            service(Filesystem::class),
            param('kernel.project_dir'),
            abstract_arg('base_directory to assets'),
        ])
        ->alias(AssetBaseDirFormatter::class, 'sensiolabs_gotenberg.asset.base_dir_formatter')
    ;

    $services->set('sensiolabs_gotenberg.twig.asset_extension', GotenbergAssetExtension::class)
        ->tag('twig.extension')
    ;

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

    $services->set('sensiolabs_gotenberg.pdf', GotenbergPdf::class)
        ->args([
            tagged_locator('sensiolabs_gotenberg.pdf_builder'),
        ])
        ->alias(GotenbergPdfInterface::class, 'sensiolabs_gotenberg.pdf')
    ;

    $services->set('sensiolabs_gotenberg', Gotenberg::class)
        ->args([
            service_locator([
                GotenbergPdfInterface::class => service(GotenbergPdfInterface::class),
                //TODO screenshot
            ])
        ])
        ->alias(GotenbergInterface::class, 'sensiolabs_gotenberg')
    ;

    //TODO for screenshot
};
