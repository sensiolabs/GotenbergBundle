<?php

namespace Sensiolabs\GotenbergBundle\DependencyInjection\CompilerPass;

use Sensiolabs\GotenbergBundle\Debug\Builder\TraceablePdfBuilder;
use Sensiolabs\GotenbergBundle\Debug\Builder\TraceableScreenshotBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class GotenbergPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $this->makePdfBuildersTraceable($container);
        $this->makeScreenshotBuildersTraceable($container);
    }

    private function makePdfBuildersTraceable(ContainerBuilder $container): void
    {
        if (!$container->has('sensiolabs_gotenberg.data_collector')) {
            return;
        }

        foreach ($container->findTaggedServiceIds('sensiolabs_gotenberg.pdf_builder') as $serviceId => $tags) {
            $container->register('.debug.'.\ltrim($serviceId, '.'), TraceablePdfBuilder::class)
                ->setDecoratedService($serviceId)
                ->setShared(false)
                ->setArguments([
                    '$inner' => new Reference('.inner'),
                    '$stopwatch' => new Reference('debug.stopwatch'),
                ])
            ;
        }
    }

    private function makeScreenshotBuildersTraceable(ContainerBuilder $container): void
    {
        if (!$container->has('sensiolabs_gotenberg.data_collector')) {
            return;
        }

        foreach ($container->findTaggedServiceIds('sensiolabs_gotenberg.screenshot_builder') as $serviceId => $tags) {
            $container->register('.debug.'.\ltrim($serviceId, '.'), TraceableScreenshotBuilder::class)
                ->setDecoratedService($serviceId)
                ->setShared(false)
                ->setArguments([
                    '$inner' => new Reference('.inner'),
                    '$stopwatch' => new Reference('debug.stopwatch'),
                ])
            ;
        }
    }
}
