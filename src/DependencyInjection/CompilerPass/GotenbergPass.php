<?php

namespace Sensiolabs\GotenbergBundle\DependencyInjection\CompilerPass;

use Sensiolabs\GotenbergBundle\Debug\Builder\TraceablePdfBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Reference;

final class GotenbergPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has('sensiolabs_gotenberg.data_collector')) {
            return;
        }

        foreach ($container->findTaggedServiceIds('sensiolabs_gotenberg.pdf_builder') as $serviceId => $tags) {
            $container->register('.debug.'.ltrim($serviceId, '.'), TraceablePdfBuilder::class)
                ->setDecoratedService($serviceId)
                ->setShared(false)
                ->setArguments([
                    '$inner' => new Reference('.inner'),
                    '$stopwatch' => new Reference('debug.stopwatch', ContainerInterface::NULL_ON_INVALID_REFERENCE),
                ])
            ;
        }
    }
}
