<?php

namespace Sensiolabs\GotenbergBundle\PayloadResolver\Behaviors\Chromium;

use Symfony\Component\OptionsResolver\OptionsResolver;

trait WaitBeforeRenderingOptionsTrait
{
    abstract protected function getBodyOptionsResolver(): OptionsResolver;

    protected function configureOptions(): void
    {
        $this->getBodyOptionsResolver()
            ->setDefined(['waitDelay', 'waitForExpression'])
            ->setAllowedTypes('waitDelay', ['string'])
            ->setAllowedValues('waitDelay', fn (mixed $value): bool => 1 === preg_match('/^\d+(s|ms)$/', $value))
            ->setAllowedTypes('waitForExpression', ['string'])
        ;
    }
}
