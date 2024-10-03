<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies;

use Twig\Environment;

trait TwigAwareTrait
{
    use DependencyAwareTrait;

    protected function getTwig(): Environment
    {
        if (
            !$this->dependencies->has('twig')
            || !($environment = $this->dependencies->get('twig')) instanceof Environment
        ) {
            throw new \LogicException(\sprintf('Twig is required to use "%s" method. Try to run "composer require symfony/twig-bundle".', __METHOD__));
        }

        return $environment;
    }
}
