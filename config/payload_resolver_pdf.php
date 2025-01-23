<?php

use Sensiolabs\GotenbergBundle\PayloadResolver\Pdf\HtmlPdfPayloadResolver;
use Sensiolabs\GotenbergBundle\PayloadResolver\Pdf\MergePdfPayloadResolver;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    // HTML
    $services->set('.sensiolabs_gotenberg.payload_resolver.html_pdf_builder', HtmlPdfPayloadResolver::class)
        ->share(false)
        ->parent('.sensiolabs_gotenberg.abstract_payload_resolver')
        ->tag('sensiolabs_gotenberg.payload_resolver')
    ;

    // Merge
    $services->set('.sensiolabs_gotenberg.payload_resolver.merge_pdf_builder', MergePdfPayloadResolver::class)
        ->share(false)
        ->parent('.sensiolabs_gotenberg.abstract_payload_resolver')
        ->tag('sensiolabs_gotenberg.payload_resolver')
    ;
};
