<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Contracts\Service\Attribute\SubscribedService;
use Symfony\Contracts\Service\ServiceSubscriberTrait;

trait SecurityTokenStorageTrait
{
    use ServiceSubscriberTrait;

    #[SubscribedService('security.token_storage')]
    protected function getTokenStorage(): TokenStorageInterface
    {
        if (
            !$this->container->has('security.token_storage')
            || !($security = $this->container->get('security.token_storage')) instanceof TokenStorageInterface
        ) {
            throw new \LogicException(\sprintf('Security is required to use "%s" method. Try to run "composer require symfony/security-bundle".', __METHOD__));
        }

        return $security;
    }
}
