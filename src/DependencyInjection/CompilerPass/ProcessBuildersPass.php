<?php

declare(strict_types=1);

namespace Sensiolabs\GotenbergBundle\DependencyInjection\CompilerPass;

use Sensiolabs\GotenbergBundle\Builder\PdfBuilderInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class ProcessBuildersPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        /** @var array<class-string<PdfBuilderInterface>, Reference> $builders */
        $builders = [];
        foreach ($container->findTaggedServiceIds('sensiolabs_gotenberg.builder') as $serviceId => $tags) {
            $definition = $container->getDefinition($serviceId);

            $builders[$definition->getClass()] = new Reference($serviceId);
        }

        $gotenberg = $container->findDefinition('sensiolabs_gotenberg');

        $gotenberg->setArgument('$container', ServiceLocatorTagPass::register($container, $builders));
    }
}
