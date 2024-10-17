<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies;

use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;

trait RequireAssetTrait
{
    abstract protected function getAsset(): AssetBaseDirFormatter;
}