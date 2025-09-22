<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Routing\RequestContext;
use Symfony\Contracts\Service\Attribute\SubscribedService;
use Symfony\Contracts\Service\ServiceSubscriberTrait;

trait RequestContextAwareTrait
{
    use ServiceSubscriberTrait;

    #[SubscribedService('.sensiolabs_gotenberg.request_context', nullable: true, attributes: new Autowire(service: '.sensiolabs_gotenberg.request_context'))]
    protected function getRequestContext(): RequestContext|null
    {
        if (!$this->container->has('.sensiolabs_gotenberg.request_context')) {
            return null;
        }

        return $this->container->get('.sensiolabs_gotenberg.request_context');
    }
}
