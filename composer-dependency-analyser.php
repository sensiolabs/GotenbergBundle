<?php

declare(strict_types=1);

use ShipMonk\ComposerDependencyAnalyser\Config\Configuration;
use ShipMonk\ComposerDependencyAnalyser\Config\ErrorType;

$config = new Configuration();

return $config
    ->addPathToScan(__DIR__.'/bin', isDev: true)
    ->addPathToScan(__DIR__.'/src', isDev: false)
    ->addPathToScan(__DIR__.'/src/DataCollector', isDev: true)
    ->addPathToScan(__DIR__.'/src/Debug', isDev: true)
    ->addPathToScan(__DIR__.'/tests', isDev: true)

    ->ignoreErrorsOnPackage('async-aws/s3', [
        ErrorType::DEV_DEPENDENCY_IN_PROD,
    ])
    ->disableExtensionsAnalysis() // TODO : Bug waiting for https://github.com/shipmonk-rnd/composer-dependency-analyser/issues/217
    ->ignoreErrorsOnPackage('league/flysystem', [
        ErrorType::DEV_DEPENDENCY_IN_PROD,
    ])
    ->ignoreErrorsOnPackage('symfony/routing', [
        ErrorType::DEV_DEPENDENCY_IN_PROD,
    ])
    ->ignoreErrorsOnPackage('twig/twig', [
        ErrorType::DEV_DEPENDENCY_IN_PROD,
    ])
;
