<?php

namespace Sensiolabs\GotenbergBundle\Builder;

interface AssetAwareBuilderInterface
{
    public function assets(string ...$pathToAssets): static;
}
