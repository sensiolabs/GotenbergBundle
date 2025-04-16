<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies;

use Psr\Log\LoggerInterface;

trait LoggerAwareTrait
{
    use DependencyAwareTrait;

    protected function getLogger(): LoggerInterface|null
    {
        if (
            !$this->dependencies->has('logger')
            || !($logger = $this->dependencies->get('logger')) instanceof LoggerInterface) {
            return null;
        }

        return $logger;
    }
}
