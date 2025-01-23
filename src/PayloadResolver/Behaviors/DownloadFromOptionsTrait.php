<?php

namespace Sensiolabs\GotenbergBundle\PayloadResolver\Behaviors;

use Sensiolabs\GotenbergBundle\PayloadResolver\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\PayloadResolver\Util\ValidatorFactory;
use Symfony\Component\OptionsResolver\OptionsResolver;

trait DownloadFromOptionsTrait
{
    abstract protected function getBodyOptionsResolver(): OptionsResolver;

    protected function configureOptions(): void
    {
        $this->getBodyOptionsResolver()
            ->define('downloadFrom')
            ->info('URLs to download files from (JSON format).')
            ->allowedTypes('string[]')
            ->allowedValues(ValidatorFactory::download())
            ->normalize(NormalizerFactory::json())
        ;
    }
}
