<?php

namespace Sensiolabs\GotenbergBundle\PayloadResolver\Behaviors\Chromium;

use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\AssetBaseDirFormatterAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Symfony\Component\OptionsResolver\OptionsResolver;

trait AssetOptionsTrait
{
    abstract protected function getBodyOptionsResolver(): OptionsResolver;

    protected function configureOptions(): void
    {
        $this->getBodyOptionsResolver()
            ->define('assets')
            ->info('Adds additional files, like images, fonts, stylesheets, and so on.')
            ->allowedTypes(\SplFileInfo::class.'[]')
        ;
    }
}
