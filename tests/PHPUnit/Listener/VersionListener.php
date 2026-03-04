<?php

declare(strict_types=1);

namespace Sensiolabs\GotenbergBundle\Tests\PHPUnit\Listener;

use PHPUnit\Event\Application\Started;
use PHPUnit\Event\Application\StartedSubscriber;
use Symfony\Component\HttpKernel\Kernel;

final class VersionListener implements StartedSubscriber
{
    public function notify(Started $event): void
    {
        $symfonyVersion = Kernel::VERSION;

        $title = "Symfony:       {$symfonyVersion}\n\n";

        fwrite(
            \STDERR,
            $title,
        );
    }
}
