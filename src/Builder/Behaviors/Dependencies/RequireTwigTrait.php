<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies;

use Twig\Environment;

trait RequireTwigTrait
{
    abstract protected function getTwig(): Environment;
}