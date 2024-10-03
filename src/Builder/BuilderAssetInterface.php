<?php

namespace Sensiolabs\GotenbergBundle\Builder;

interface BuilderAssetInterface
{
    public function addAsset(string $path): static;
}
