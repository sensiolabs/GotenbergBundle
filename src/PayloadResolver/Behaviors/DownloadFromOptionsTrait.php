<?php

namespace Sensiolabs\GotenbergBundle\PayloadResolver\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\Util\ValidatorFactory;
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
        ;
    }
}
