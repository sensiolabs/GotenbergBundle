<?php

namespace Sensiolabs\GotenbergBundle\DependencyInjection\CompilerPass;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Debug\Builder\TraceableBuilder;
use Sensiolabs\GotenbergBundle\DependencyInjection\SensiolabsGotenbergExtension;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Reference;

final class GotenbergPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        /** @var SensiolabsGotenbergExtension $extension */
        $extension = $container->getExtension('sensiolabs_gotenberg');
        $builderPerType = [];
        foreach ($container->findTaggedServiceIds('sensiolabs_gotenberg.builder') as $serviceId => $tags) {
            $serviceDefinition = $container->getDefinition($serviceId);
            $serviceDefinition
                ->setShared(false)
                ->addTag('container.service_subscriber')
            ;

            /** @var class-string<BuilderInterface> $class */
            $class = $serviceDefinition->getClass();
            $type = $extension->getBuilder($class);

            $builderPerType[$type] ??= [];
            $builderPerType[$type][$serviceId] = new Reference($serviceId);
        }

        if ($container->hasDefinition('sensiolabs_gotenberg.pdf')) {
            $container->getDefinition('sensiolabs_gotenberg.pdf')
                ->replaceArgument(0, ServiceLocatorTagPass::register($container, $builderPerType['pdf']))
            ;
        }

        if ($container->hasDefinition('sensiolabs_gotenberg.screenshot')) {
            $container->getDefinition('sensiolabs_gotenberg.screenshot')
                ->replaceArgument(0, ServiceLocatorTagPass::register($container, $builderPerType['screenshot']))
            ;
        }

        if (!$container->has('sensiolabs_gotenberg.data_collector')) {
            return;
        }

        foreach ($container->findTaggedServiceIds('sensiolabs_gotenberg.builder') as $serviceId => $tags) {
            $container
                ->register('.debug.'.ltrim($serviceId, '.'), TraceableBuilder::class)
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
