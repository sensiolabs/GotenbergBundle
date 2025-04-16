<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies;

use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;

trait AssetBaseDirFormatterAwareTrait
{
    use DependencyAwareTrait;

    protected function getAssetBaseDirFormatter(): AssetBaseDirFormatter
    {
        return $this->dependencies->get('asset_base_dir_formatter');
    }
}
