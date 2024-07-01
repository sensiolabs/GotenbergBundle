<?php

namespace Sensiolabs\GotenbergBundle\DependencyInjection\CompilerPass;

use Sensiolabs\GotenbergBundle\Debug\Builder\TraceablePdfBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Stopwatch\Stopwatch;

final class GotenbergPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has('sensiolabs_gotenberg.data_collector')) {
            return;
        }

        $stopwatch = ContainerBuilder::willBeAvailable('symfony/stopwatch', Stopwatch::class, ['symfony/framework-bundle'])
            ? new Reference('debug.stopwatch') : null;

        foreach ($container->findTaggedServiceIds('sensiolabs_gotenberg.pdf_builder') as $serviceId => $tags) {
            $container->register('.debug.'.ltrim($serviceId, '.'), TraceablePdfBuilder::class)
                ->setDecoratedService($serviceId)
                ->setShared(false)
                ->setArguments([
                    '$inner' => new Reference('.inner'),
                    '$stopwatch' => $stopwatch,
                ])
            ;
        }
    }
}
