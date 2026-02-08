<?php

declare(strict_types=1);

namespace Sensiolabs\GotenbergBundle\Tests\PHPUnit;

use PHPUnit\Runner\Extension\Extension;
use PHPUnit\Runner\Extension\Facade;
use PHPUnit\Runner\Extension\ParameterCollection;
use PHPUnit\TextUI\Configuration\Configuration;
use Sensiolabs\GotenbergBundle\Tests\PHPUnit\Listener\VersionListener;

final class GotenbergExtension implements Extension
{
    public function bootstrap(Configuration $configuration, Facade $facade, ParameterCollection $parameters): void
    {
        $facade->registerSubscriber(
            new VersionListener(),
        );
    }
}
