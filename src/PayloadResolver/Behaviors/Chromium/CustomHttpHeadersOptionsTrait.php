<?php

namespace Sensiolabs\GotenbergBundle\PayloadResolver\Behaviors\Chromium;

use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Symfony\Component\OptionsResolver\OptionsResolver;

trait CustomHttpHeadersOptionsTrait
{
    abstract protected function getBodyOptionsResolver(): OptionsResolver;

    protected function configureOptions(): void
    {
        $this->getBodyOptionsResolver()
            ->define('userAgent')
            ->info('Override the default User-Agent HTTP header.')
            ->allowedTypes('string')
        ;

        $this->getBodyOptionsResolver()
            ->define('extraHttpHeaders')
            ->info('Extra HTTP headers to send by Chromium (JSON format).')
            ->allowedTypes('string[]')
            ->normalize(NormalizerFactory::json())
        ;
    }
}
