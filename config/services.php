<?php

use Sensiolabs\GotenbergBundle\Builder\HtmlPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\LibreOfficePdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\MarkdownPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\UrlPdfBuilder;
use Sensiolabs\GotenbergBundle\Client\GotenbergClient;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Sensiolabs\GotenbergBundle\Pdf\Gotenberg;
use Sensiolabs\GotenbergBundle\Pdf\GotenbergInterface;
use Sensiolabs\GotenbergBundle\Twig\GotenbergAssetExtension;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use function Symfony\Component\DependencyInjection\Loader\Configurator\abstract_arg;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set('sensiolabs_gotenberg.client', GotenbergClient::class)
        ->args([
            abstract_arg('base_uri to gotenberg API'),
            service(HttpClientInterface::class),
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

    $services->set('.sensiolabs_gotenberg.builder.html', HtmlPdfBuilder::class)
        ->share(false)
        ->args([
            service('sensiolabs_gotenberg.client'),
            service('sensiolabs_gotenberg.asset.base_dir_formatter'),
            service('twig')->nullOnInvalid(),
        ])
        ->tag('sensiolabs_gotenberg.builder')
    ;

    $services->set('.sensiolabs_gotenberg.builder.url', UrlPdfBuilder::class)
        ->share(false)
        ->args([
            service('sensiolabs_gotenberg.client'),
            service('sensiolabs_gotenberg.asset.base_dir_formatter'),
            service('twig')->nullOnInvalid(),
        ])
        ->tag('sensiolabs_gotenberg.builder')
    ;

    $services->set('.sensiolabs_gotenberg.builder.markdown', MarkdownPdfBuilder::class)
        ->share(false)
        ->args([
            service('sensiolabs_gotenberg.client'),
            service('sensiolabs_gotenberg.asset.base_dir_formatter'),
            service('twig')->nullOnInvalid(),
        ])
        ->tag('sensiolabs_gotenberg.builder')
    ;

    $services->set('.sensiolabs_gotenberg.builder.office', LibreOfficePdfBuilder::class)
        ->share(false)
        ->args([
            service('sensiolabs_gotenberg.client'),
            service('sensiolabs_gotenberg.asset.base_dir_formatter'),
        ])
        ->tag('sensiolabs_gotenberg.builder')
    ;

    $services->set('sensiolabs_gotenberg', Gotenberg::class)
        ->args([
            abstract_arg('All builders indexed by class FQCN')
        ])
        ->alias(GotenbergInterface::class, 'sensiolabs_gotenberg')
    ;
};
