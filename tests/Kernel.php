<?php

namespace Sensiolabs\GotenbergBundle\Tests;

use Sensiolabs\GotenbergBundle\GotenbergInterface;
use Sensiolabs\GotenbergBundle\SensiolabsGotenbergBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

final class Kernel extends BaseKernel implements CompilerPassInterface
{
    use MicroKernelTrait;

    public function getCacheDir(): string
    {
        return __DIR__.'/../var/cache';
    }

    public function getLogDir(): string
    {
        return __DIR__.'/../var/log';
    }

    private function configureContainer(ContainerConfigurator $container, LoaderInterface $loader, ContainerBuilder $builder): void
    {
        $builder->loadFromExtension('framework', [
            'test' => true,
        ]);
        $builder->addCompilerPass($this);
    }

    public function registerBundles(): iterable
    {
        yield new FrameworkBundle();
        yield new SensiolabsGotenbergBundle();
    }

    public function process(ContainerBuilder $container): void
    {
        $container->getAlias(GotenbergInterface::class)->setPublic(true);
    }
}
