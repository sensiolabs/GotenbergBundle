<?php

namespace Sensiolabs\GotenbergBundle\PayloadResolver;

use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractPayloadResolver implements PayloadResolverInterface
{
    private OptionsResolver $bodyOptionsResolver;
    private OptionsResolver $headersOptionsResolver;

    public function __construct(
        protected readonly string $gotenbergApiVersion,
    ) {
        $this->bodyOptionsResolver = new OptionsResolver();
        $this->headersOptionsResolver = new OptionsResolver();

        $this->configureOptions();
    }

    public function getBodyOptionsResolver(): OptionsResolver
    {
        return $this->bodyOptionsResolver;
    }

    public function getHeadersOptionsResolver(): OptionsResolver
    {
        $this->headersOptionsResolver
            ->define('Gotenberg-Output-Filename')
            ->allowedTypes('string')
        ;

        return $this->headersOptionsResolver;
    }

    abstract protected function configureOptions(): void;
}
