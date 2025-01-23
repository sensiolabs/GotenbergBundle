<?php

namespace Sensiolabs\GotenbergBundle\PayloadResolver\Behaviors\Chromium;

use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\Builder\Util\ValidatorFactory;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\OptionsResolver\OptionsResolver;

trait CookieOptionsTrait
{
    abstract protected function getBodyOptionsResolver(): OptionsResolver;

    protected function configureOptions(): void
    {
        $this->getBodyOptionsResolver()
            ->define('cookies')
            ->info('Cookies to store in the Chromium cookie jar (JSON format).')
            ->allowedTypes('array[]', Cookie::class.'[]')
            ->allowedValues(ValidatorFactory::cookies())
            ->normalize(NormalizerFactory::json(false))
        ;
    }
}
