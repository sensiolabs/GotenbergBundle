<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Service\Attribute\SubscribedService;
use Symfony\Contracts\Service\ServiceSubscriberTrait;

trait RequestAwareTrait
{
    use ServiceSubscriberTrait;

    #[SubscribedService('request_stack', nullable: true)]
    protected function getCurrentRequest(): Request|null
    {
        if (
            !$this->container->has('request_stack')
            || !($requestStack = $this->container->get('request_stack')) instanceof RequestStack
        ) {
            throw new \LogicException(\sprintf('RequestStack is required to use "%s" method. Try to run "composer require symfony/http-foundation".', __METHOD__));
        }

        return $requestStack->getCurrentRequest();
    }
}
