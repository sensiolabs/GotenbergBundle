<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Service\Attribute\SubscribedService;
use Symfony\Contracts\Service\ServiceMethodsSubscriberTrait;

trait UrlGeneratorAwareTrait
{
    use ServiceMethodsSubscriberTrait;

    #[SubscribedService('router', nullable: true)]
    protected function getUrlGenerator(): UrlGeneratorInterface
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
