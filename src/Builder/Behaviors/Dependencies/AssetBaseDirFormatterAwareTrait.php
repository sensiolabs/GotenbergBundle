<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies;

use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Symfony\Contracts\Service\Attribute\SubscribedService;
use Symfony\Contracts\Service\ServiceMethodsSubscriberTrait;

trait AssetBaseDirFormatterAwareTrait
{
    use ServiceMethodsSubscriberTrait;

    #[SubscribedService('asset_base_dir_formatter')]
    protected function getAssetBaseDirFormatter(): AssetBaseDirFormatter
    {
        return $this->container->get('asset_base_dir_formatter');
    }
}
