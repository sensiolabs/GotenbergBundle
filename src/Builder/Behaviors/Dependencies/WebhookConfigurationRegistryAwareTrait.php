<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies;

use Sensiolabs\GotenbergBundle\Webhook\WebhookConfigurationRegistryInterface;
use Symfony\Contracts\Service\Attribute\SubscribedService;
use Symfony\Contracts\Service\ServiceMethodsSubscriberTrait;

trait WebhookConfigurationRegistryAwareTrait
{
    use ServiceMethodsSubscriberTrait;

    #[SubscribedService('webhook_configuration_registry')]
    protected function getWebhookConfigurationRegistry(): WebhookConfigurationRegistryInterface
    {
        return $this->container->get('webhook_configuration_registry');
    }
}
