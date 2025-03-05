<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

trait RequestAwareTrait
{
    use DependencyAwareTrait;

    protected function getCurrentRequest(): Request|null
    {
        if (
            !$this->dependencies->has('request_stack')
            || !($requestStack = $this->dependencies->get('request_stack')) instanceof RequestStack
        ) {
            throw new \LogicException(\sprintf('RequestStack is required to use "%s" method. Try to run "composer require symfony/http-foundation".', __METHOD__));
        }

        return $requestStack->getCurrentRequest();
    }
}
