<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies;

use Symfony\Contracts\Service\Attribute\SubscribedService;
use Symfony\Contracts\Service\ServiceMethodsSubscriberTrait;
use Twig\Environment;

trait TwigAwareTrait
{
    use ServiceMethodsSubscriberTrait;

    #[SubscribedService('twig', nullable: true)]
    protected function getTwig(): Environment
    {
        if (
            !$this->container->has('twig')
            || !($environment = $this->container->get('twig')) instanceof Environment
        ) {
            throw new \LogicException(\sprintf('Twig is required to use "%s" method. Try to run "composer require symfony/twig-bundle".', __METHOD__));
        }

        return $environment;
    }
}
