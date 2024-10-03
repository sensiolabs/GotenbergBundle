<?php

namespace Sensiolabs\GotenbergBundle\DependencyInjection\CompilerPass;

use Sensiolabs\GotenbergBundle\Debug\Builder\TraceableBuilder;
use Sensiolabs\GotenbergBundle\DependencyInjection\BuilderStack;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Reference;

final class GotenbergPass implements CompilerPassInterface
{
    public function __construct(
        private readonly BuilderStack $builderStack,
    ) {
    }

    public function process(ContainerBuilder $container): void
    {
        $builderPerType = [];
        foreach ($container->findTaggedServiceIds('sensiolabs_gotenberg.builder') as $serviceId => $tags) {
            $serviceDefinition = $container->getDefinition($serviceId);
            $serviceDefinition
                ->setShared(false)
            ;

            $class = $serviceDefinition->getClass();

            $type = $this->builderStack->getBuilders()[$class];

            $builderPerType[$type] ??= [];
            $builderPerType[$type][] = new Reference($serviceId);
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
