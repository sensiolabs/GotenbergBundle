<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\Service\Attribute\SubscribedService;
use Symfony\Contracts\Service\ServiceMethodsSubscriberTrait;

trait LoggerAwareTrait
{
    use ServiceMethodsSubscriberTrait;

    #[SubscribedService('logger', nullable: true)]
    protected function getLogger(): LoggerInterface|null
    {
        if (
            !$this->container->has('logger')
            || !($logger = $this->container->get('logger')) instanceof LoggerInterface) {
            return null;
        }

        return $logger;
    }
}
