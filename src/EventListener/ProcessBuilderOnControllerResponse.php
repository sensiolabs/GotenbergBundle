<?php

declare(strict_types=1);

namespace Sensiolabs\GotenbergBundle\EventListener;

use Sensiolabs\GotenbergBundle\Builder\GotenbergFileResult;
use Symfony\Component\HttpKernel\Event\ViewEvent;

final class ProcessBuilderOnControllerResponse
{
    public function streamBuilder(ViewEvent $event): void
    {
        $controllerResult = $event->getControllerResult();

        if (!$controllerResult instanceof GotenbergFileResult) {
            return;
        }

        $event->setResponse($controllerResult->stream());
    }
}
