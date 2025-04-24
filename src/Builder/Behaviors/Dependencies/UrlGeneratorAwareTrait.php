<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Service\Attribute\SubscribedService;
use Symfony\Contracts\Service\ServiceSubscriberTrait;

/**
 * @method UrlGeneratorInterface getUrlGenerator()
 */
trait UrlGeneratorAwareTrait
{
    use ServiceSubscriberTrait;

    #[SubscribedService('router', nullable: true)]
    protected function getUrlGenerator(): UrlGeneratorInterface|null
    {
        if (
            !$this->container->has('router')
            || !($urlGenerator = $this->container->get('router')) instanceof UrlGeneratorInterface
        ) {
            throw new \LogicException(\sprintf('UrlGenerator is required to use "%s" method. Try to run "composer require symfony/routing".', __METHOD__));
        }

        return $urlGenerator;
    }
}
