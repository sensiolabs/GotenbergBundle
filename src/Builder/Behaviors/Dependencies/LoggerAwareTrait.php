<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies;

use Psr\Log\LoggerInterface;
use Twig\Environment;

trait LoggerAwareTrait
{
    use DependencyAwareTrait;

    protected function getLogger(): LoggerInterface|null
    {
        if (($logger = $this->dependencies->get('logger')) instanceof Environment) {
            return $logger;
        }

        return null;
    }
}