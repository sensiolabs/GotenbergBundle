<?php

namespace Sensiolabs\GotenbergBundle\PayloadResolver\Behaviors;

use Sensiolabs\GotenbergBundle\Enumeration\SplitMode;
use Symfony\Component\OptionsResolver\OptionsResolver;

trait SplitOptionsTrait
{
    abstract protected function getBodyOptionsResolver(): OptionsResolver;

    protected function configureOptions(): void
    {
        $this->getBodyOptionsResolver()
            ->define('splitMode')
            ->info('Either intervals or pages.')
            ->allowedValues(...SplitMode::cases())
        ;
        $this->getBodyOptionsResolver()
            ->define('splitSpan')
            ->info('Either the intervals or the page ranges to extract, depending on the selected mode.')
            ->allowedTypes('string')
        ;
        $this->getBodyOptionsResolver()
            ->define('splitUnify')
            ->info('Specify whether to put extracted pages into a single file or as many files as there are page ranges. Only works with pages mode.')
            ->allowedTypes('bool')
        ;
    }
}
