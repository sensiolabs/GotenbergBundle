<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

trait UrlGeneratorAwareTrait
{
    use DependencyAwareTrait;

    protected function getUrlGenerator(): UrlGeneratorInterface
    {
        if (
            !$this->dependencies->has('urlGenerator')
            || !($urlGenerator = $this->dependencies->get('urlGenerator')) instanceof UrlGeneratorInterface
        ) {
            throw new \LogicException(\sprintf('UrlGenerator is required to use "%s" method. Try to run "composer require symfony/router".', __METHOD__));
        }

        return $urlGenerator;
    }
}
