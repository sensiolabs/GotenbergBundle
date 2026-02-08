<?php

declare(strict_types=1);

namespace Sensiolabs\GotenbergBundle\Tests\PHPUnit\Listener;

use PHPUnit\Event\Application\Started;
use PHPUnit\Event\Application\StartedSubscriber;
use Symfony\Component\HttpKernel\Kernel;
use function fwrite;
use function rtrim;
use const STDERR;

final class VersionListener implements StartedSubscriber
{
    public function notify(Started $event): void
    {
        $symfonyVersion = rtrim(Kernel::VERSION . '-' . Kernel::EXTRA_VERSION, '-');

        $title = "Symfony:       {$symfonyVersion}\n\n";

        fwrite(
            STDERR,
            $title,
        );
    }
}
