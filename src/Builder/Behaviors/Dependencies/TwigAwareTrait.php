<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies;

use Psr\Container\ContainerInterface;
use Twig\Environment;

trait TwigAwareTrait
{
    protected readonly ContainerInterface $dependencies;

    protected function getTwig(): Environment
    {
        if (!($environment = $this->dependencies->get('twig')) instanceof Environment) {
            throw new \LogicException(\sprintf('Twig is required to use "%s" method. Try to run "composer require symfony/twig-bundle".', __METHOD__));
        }

        return $environment;
    }
}