<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Chromium;

use Sensiolabs\GotenbergBundle\Client\BodyBag;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @see https://gotenberg.dev/docs/routes#page-properties-chromium
 */
trait PagePropertiesTrait
{
    abstract protected function getBodyBag(): BodyBag;

    protected function configure(OptionsResolver $bodyOptionsResolver, OptionsResolver $headersOptionsResolver): void
    {
        $normalizeUnit = static fn (Options $options, $value): string => is_numeric($value) ? $value.'in' : (string) $value;
        // See https://regex101.com/r/XUK2Ip/1
        $validateRange = static fn (string $value): bool => 1 === preg_match('/^ *(\d+ *(- *\d+)? *, *)*\d+ *(- *\d+)? *$/', $value);

        $bodyOptionsResolver
            ->define('singlePage')
            ->info('Define whether to print the entire content in one single page.')
            ->allowedTypes('bool')
        ;
        $bodyOptionsResolver
            ->define('paperWidth')
            ->info('Specify paper width using units like 72pt, 96px, 1in, 25.4mm, 2.54cm, or 6pc. Default unit is inches if unspecified.')
            ->normalize($normalizeUnit)
        ;
        $bodyOptionsResolver
            ->define('paperHeight')
            ->info('Specify paper height using units like 72pt, 96px, 1in, 25.4mm, 2.54cm, or 6pc. Default unit is inches if unspecified.')
            ->normalize($normalizeUnit)
        ;
        $bodyOptionsResolver
            ->define('marginTop')
            ->info('Specify top margin width using units like 72pt, 96px, 1in, 25.4mm, 2.54cm, or 6pc. Default unit is inches if unspecified.')
            ->normalize($normalizeUnit)
        ;
        $bodyOptionsResolver
            ->define('marginBottom')
            ->info('Specify bottom margin using units like 72pt, 96px, 1in, 25.4mm, 2.54cm, or 6pc. Default unit is inches if unspecified.')
            ->normalize($normalizeUnit)
        ;
        $bodyOptionsResolver
            ->define('marginLeft')
            ->info('Specify left margin using units like 72pt, 96px, 1in, 25.4mm, 2.54cm, or 6pc. Default unit is inches if unspecified.')
            ->normalize($normalizeUnit)
        ;
        $bodyOptionsResolver
            ->define('marginRight')
            ->info('Specify right margin using units like 72pt, 96px, 1in, 25.4mm, 2.54cm, or 6pc. Default unit is inches if unspecified.')
            ->normalize($normalizeUnit)
        ;
        $bodyOptionsResolver
            ->define('preferCssPageSize')
            ->info('Define whether to prefer page size as defined by CSS.')
            ->allowedTypes('bool')
        ;
        $bodyOptionsResolver
            ->define('printBackground')
            ->info('Print the background graphics.')
            ->allowedTypes('bool')
        ;
        $bodyOptionsResolver
            ->define('omitBackground')
            ->info('Hide the default white background and allow generating PDFs with transparency.')
            ->allowedTypes('bool')
        ;
        $bodyOptionsResolver
            ->define('landscape')
            ->info('Set the paper orientation to landscape.')
            ->allowedTypes('bool')
        ;
        $bodyOptionsResolver
            ->define('scale')
            ->info('The scale of the page rendering.')
            ->allowedTypes('int', 'float')
        ;
        $bodyOptionsResolver
            ->define('nativePageRanges')
            ->info("Page ranges to print, e.g., '1-5, 8, 11-13' - empty means all pages.")
            ->allowedValues($validateRange)
        ;
    }

    /**
     * Define whether to print the entire content in one single page.
     *
     * If the singlePage form field is set to true, it automatically overrides the values from the paperHeight and nativePageRanges form fields.
     */
    public function singlePage(bool $singlePage = true): static
    {
        $this->getBodyBag()->set('singlePage', $singlePage);

        return $this;
    }
}
