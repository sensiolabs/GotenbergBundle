<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Fixtures;

use Sensiolabs\GotenbergBundle\Builder\AbstractBuilder;

abstract class AbstractTestBuilder extends AbstractBuilder
{
    use BehaviorTestTrait;

    protected function getEndpoint(): string
    {
        return '/test';
    }
}
