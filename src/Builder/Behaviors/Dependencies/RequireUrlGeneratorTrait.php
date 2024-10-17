<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

trait RequireUrlGeneratorTrait
{
    abstract protected function getUrlGenerator(): UrlGeneratorInterface;
}