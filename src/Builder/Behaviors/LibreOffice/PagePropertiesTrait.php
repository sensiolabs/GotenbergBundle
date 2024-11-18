<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\LibreOffice;

use Sensiolabs\GotenbergBundle\Client\BodyBag;
use Symfony\Component\OptionsResolver\OptionsResolver;

trait PagePropertiesTrait
{
    abstract protected function getBodyBag(): BodyBag;

    protected function configure(OptionsResolver $bodyOptionsResolver, OptionsResolver $headersOptionsResolver): void
    {
        $bodyOptionsResolver
            ->define('landscape')
            ->info('Set the paper orientation to landscape.')
            ->allowedTypes('bool')
        ;

        // @TODO
    }

    /**
     * Sets the paper orientation to landscape.
     */
    public function landscape(bool $bool = true): self
    {
        $this->getBodyBag()->set('landscape', $bool);

        return $this;
    }
}