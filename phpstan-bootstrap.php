<?php

declare(strict_types=1);

use Symfony\Component\HttpKernel\Kernel;

require __DIR__ . '/vendor/autoload.php';

$symfonyVersion = Kernel::VERSION;
$phpVersion = PHP_VERSION;

echo "Symfony:  {$symfonyVersion}\n";
echo "PHP:      {$phpVersion}\n";
echo "-----------------------------\n";
