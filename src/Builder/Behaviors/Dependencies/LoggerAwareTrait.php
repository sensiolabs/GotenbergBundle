<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies;

use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Container;

trait LoggerAwareTrait
{
    use DependencyAwareTrait;

    protected function getLogger(): LoggerInterface|null
    {
        if (($logger = $this->dependencies->get('logger', Container::NULL_ON_INVALID_REFERENCE)) instanceof LoggerInterface) {
            return $logger;
        }

        return null;
    }
}
