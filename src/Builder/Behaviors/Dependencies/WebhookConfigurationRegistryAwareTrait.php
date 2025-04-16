<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies;

use Sensiolabs\GotenbergBundle\Webhook\WebhookConfigurationRegistryInterface;

trait WebhookConfigurationRegistryAwareTrait
{
    use DependencyAwareTrait;

    protected function getWebhookConfigurationRegistry(): WebhookConfigurationRegistryInterface
    {
        return $this->dependencies->get('webhook_configuration_registry');
    }
}
