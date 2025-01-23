<?php

namespace Sensiolabs\GotenbergBundle\PayloadResolver\Behaviors;

use Sensiolabs\GotenbergBundle\PayloadResolver\Util\NormalizerFactory;
use Symfony\Component\OptionsResolver\OptionsResolver;

trait MetadataOptionsTrait
{
    abstract protected function getBodyOptionsResolver(): OptionsResolver;

    protected function configureOptions(): void
    {
        $this->getBodyOptionsResolver()
            ->define('metadata')
            ->info('Add metadata.')
            ->normalize(NormalizerFactory::json())
        ;
    }
}
