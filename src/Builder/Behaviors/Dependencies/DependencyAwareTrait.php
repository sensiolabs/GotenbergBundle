<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies;

use Psr\Container\ContainerInterface;

trait DependencyAwareTrait
{
    protected readonly ContainerInterface $dependencies;
}
