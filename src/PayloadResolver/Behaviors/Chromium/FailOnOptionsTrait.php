<?php

namespace Sensiolabs\GotenbergBundle\PayloadResolver\Behaviors\Chromium;

use Sensiolabs\GotenbergBundle\PayloadResolver\Util\NormalizerFactory;
use Symfony\Component\OptionsResolver\OptionsResolver;

trait FailOnOptionsTrait
{
    abstract protected function getBodyOptionsResolver(): OptionsResolver;

    protected function configureOptions(): void
    {
        $this->getBodyOptionsResolver()
            ->define('failOnHttpStatusCodes')
            ->info('Return a 409 Conflict response if the HTTP status code from the main page is not acceptable.')
            ->allowedTypes('int[]')
            ->normalize(NormalizerFactory::json(false))
        ;

        $this->getBodyOptionsResolver()
            ->define('failOnResourceHttpStatusCodes')
            ->info('Return a 409 Conflict response if the HTTP status code from at least one resource is not acceptable.')
            ->allowedTypes('int[]')
            ->normalize(NormalizerFactory::json(false))
        ;

        $this->getBodyOptionsResolver()
            ->define('failOnResourceLoadingFailed')
            ->info('Return a 409 Conflict response if Chromium fails to load at least one resource.')
            ->allowedTypes('bool')
            ->normalize(NormalizerFactory::bool())
        ;

        $this->getBodyOptionsResolver()
            ->define('failOnConsoleExceptions')
            ->info('Return a 409 Conflict response if there are exceptions in the Chromium console.')
            ->allowedTypes('bool')
            ->normalize(NormalizerFactory::bool())
        ;
    }
}
