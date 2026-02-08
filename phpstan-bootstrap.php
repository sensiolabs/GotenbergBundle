<?php

declare(strict_types=1);

use Symfony\Component\HttpKernel\Kernel;

require __DIR__ . '/vendor/autoload.php';

$symfonyVersion = rtrim(Kernel::VERSION . '-' . Kernel::EXTRA_VERSION, '-');
$phpVersion = PHP_VERSION;

echo "Symfony:  {$symfonyVersion}\n";
echo "PHP:      {$phpVersion}\n";
echo "-----------------------------\n";
