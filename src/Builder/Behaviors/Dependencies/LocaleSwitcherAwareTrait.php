<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies;

use Symfony\Component\Translation\LocaleSwitcher;
use Symfony\Contracts\Service\Attribute\SubscribedService;
use Symfony\Contracts\Service\ServiceMethodsSubscriberTrait;

trait LocaleSwitcherAwareTrait
{
    use ServiceMethodsSubscriberTrait;

    #[SubscribedService('translation.locale_switcher', nullable: true)]
    protected function getLocaleSwitcher(): LocaleSwitcher
    {
        if (
            !$this->container->has('translation.locale_switcher')
            || !($localeSwitcher = $this->container->get('translation.locale_switcher')) instanceof LocaleSwitcher
        ) {
            throw new \LogicException(\sprintf('Symfony Translation is required to use "%s" method. Try to run "composer require symfony/translation".', __METHOD__));
        }

        return $localeSwitcher;
    }
}
