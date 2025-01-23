<?php

namespace Sensiolabs\GotenbergBundle\PayloadResolver\Behaviors\Chromium;

use Sensiolabs\GotenbergBundle\Enumeration\EmulatedMediaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

trait EmulatedMediaTypeOptionsTrait
{
    abstract protected function getBodyOptionsResolver(): OptionsResolver;

    protected function configureOptions(): void
    {
        $this->getBodyOptionsResolver()
            ->define('emulatedMediaType')
            ->info('Forces Chromium to emulate, either "screen" or "print".')
            ->allowedValues(...EmulatedMediaType::cases())
        ;
    }
}
