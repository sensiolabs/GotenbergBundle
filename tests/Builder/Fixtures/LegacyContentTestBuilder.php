<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Fixtures;

use Sensiolabs\GotenbergBundle\Builder\AbstractBuilder;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Chromium\AssetTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Chromium\ContentTrait;
use Sensiolabs\GotenbergBundle\Builder\BuilderAssetInterface;

/**
 * Third party builder relying on the deprecated trait. To be removed in 2.0 along with it.
 */
final class LegacyContentTestBuilder extends AbstractBuilder implements BuilderAssetInterface
{
    use AssetTrait;
    use ContentTrait;

    protected function getEndpoint(): string
    {
        return '/test';
    }
}
