<?php

namespace Sensiolabs\GotenbergBundle\PayloadResolver\Behaviors\Chromium;

use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\PayloadResolver\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\PayloadResolver\Util\ValidatorFactory;
use Sensiolabs\GotenbergBundle\Enumeration\PaperSizeInterface;
use Sensiolabs\GotenbergBundle\Enumeration\Unit;
use Symfony\Component\OptionsResolver\OptionsResolver;

trait PagePropertiesOptionsTrait
{
    abstract protected function getBodyOptionsResolver(): OptionsResolver;

    protected function configureOptions(): void
    {
        $this->getBodyOptionsResolver()
            ->define('singlePage')
            ->info('Define whether to print the entire content in one single page.')
            ->allowedTypes('bool')
            ->normalize(NormalizerFactory::bool())
        ;
        $this->getBodyOptionsResolver()
            ->define('paperWidth')
            ->info('Specify paper width using units like 72pt, 96px, 1in, 25.4mm, 2.54cm, or 6pc. Default unit is inches if unspecified.')
            ->normalize(NormalizerFactory::unit())
        ;
        $this->getBodyOptionsResolver()
            ->define('paperHeight')
            ->info('Specify paper height using units like 72pt, 96px, 1in, 25.4mm, 2.54cm, or 6pc. Default unit is inches if unspecified.')
            ->normalize(NormalizerFactory::unit())
        ;
        $this->getBodyOptionsResolver()
            ->define('marginTop')
            ->info('Specify top margin width using units like 72pt, 96px, 1in, 25.4mm, 2.54cm, or 6pc. Default unit is inches if unspecified.')
            ->normalize(NormalizerFactory::unit())
        ;
        $this->getBodyOptionsResolver()
            ->define('marginBottom')
            ->info('Specify bottom margin using units like 72pt, 96px, 1in, 25.4mm, 2.54cm, or 6pc. Default unit is inches if unspecified.')
            ->normalize(NormalizerFactory::unit())
        ;
        $this->getBodyOptionsResolver()
            ->define('marginLeft')
            ->info('Specify left margin using units like 72pt, 96px, 1in, 25.4mm, 2.54cm, or 6pc. Default unit is inches if unspecified.')
            ->normalize(NormalizerFactory::unit())
        ;
        $this->getBodyOptionsResolver()
            ->define('marginRight')
            ->info('Specify right margin using units like 72pt, 96px, 1in, 25.4mm, 2.54cm, or 6pc. Default unit is inches if unspecified.')
            ->normalize(NormalizerFactory::unit())
        ;
        $this->getBodyOptionsResolver()
            ->define('preferCssPageSize')
            ->info('Define whether the document outline should be embedded into the PDF.')
            ->allowedTypes('bool')
            ->normalize(NormalizerFactory::bool())
        ;
        $this->getBodyOptionsResolver()
            ->define('generateDocumentOutline')
            ->info('Define whether to prefer page size as defined by CSS.')
            ->allowedTypes('bool')
            ->normalize(NormalizerFactory::bool())
        ;
        $this->getBodyOptionsResolver()
            ->define('printBackground')
            ->info('Print the background graphics.')
            ->allowedTypes('bool')
            ->normalize(NormalizerFactory::bool())
        ;
        $this->getBodyOptionsResolver()
            ->define('omitBackground')
            ->info('Hide the default white background and allow generating PDFs with transparency.')
            ->allowedTypes('bool')
            ->normalize(NormalizerFactory::bool())
        ;
        $this->getBodyOptionsResolver()
            ->define('landscape')
            ->info('Set the paper orientation to landscape.')
            ->allowedTypes('bool')
            ->normalize(NormalizerFactory::bool())
        ;
        $this->getBodyOptionsResolver()
            ->define('scale')
            ->info('The scale of the page rendering.')
            ->allowedTypes('int', 'float')
            ->normalize(NormalizerFactory::scale())
        ;
        $this->getBodyOptionsResolver()
            ->define('nativePageRanges')
            ->info("Page ranges to print, e.g., '1-5, 8, 11-13' - empty means all pages.")
            ->allowedValues(ValidatorFactory::range())
        ;
    }
}
