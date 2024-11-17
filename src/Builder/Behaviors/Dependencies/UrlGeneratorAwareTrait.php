<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies;

use Psr\Container\ContainerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

trait UrlGeneratorAwareTrait
{
    protected readonly ContainerInterface $dependencies;

    protected function getUrlGenerator(): UrlGeneratorInterface
    {
        if (!($urlGenerator = $this->dependencies->get('urlGenerator')) instanceof UrlGeneratorInterface) {
            throw new \LogicException(\sprintf('UrlGenerator is required to use "%s" method. Try to run "composer require symfony/router".', __METHOD__));
        }

        return $urlGenerator;
    }
}
