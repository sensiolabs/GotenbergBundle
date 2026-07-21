<?php

namespace Sensiolabs\GotenbergBundle\Tests\Integration;

use Psr\Log\NullLogger;
use Sensiolabs\GotenbergBundle\GotenbergInterface;
use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;
use Sensiolabs\GotenbergBundle\GotenbergScreenshotInterface;
use Sensiolabs\GotenbergBundle\SensiolabsGotenbergBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

final class Kernel extends BaseKernel implements CompilerPassInterface
{
    use MicroKernelTrait;

    public function getProjectDir(): string
    {
        return \dirname(__DIR__, 2);
    }

    public function getCacheDir(): string
    {
        return $this->getProjectDir().'/var/cache/integration';
    }

    public function getLogDir(): string
    {
        return $this->getProjectDir().'/var/log/integration';
    }

    protected function configureContainer(ContainerConfigurator $container, LoaderInterface $loader, ContainerBuilder $builder): void
    {
        $builder->loadFromExtension('framework', [
            'test' => true,
            'http_client' => [
                'scoped_clients' => [
                    'gotenberg' => [
                        'base_uri' => $this->getGotenbergBaseUri(),
                    ],
                ],
            ],
        ]);

        $builder->loadFromExtension('sensiolabs_gotenberg', [
            'http_client' => 'gotenberg',
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
        $container->getAlias(GotenbergPdfInterface::class)->setPublic(true);
        $container->getAlias(GotenbergScreenshotInterface::class)->setPublic(true);
        $container->setDefinition('logger', new Definition(NullLogger::class));
    }

    private function getGotenbergBaseUri(): string
    {
        $baseUri = getenv('GOTENBERG_BASE_URI');

        if (false === $baseUri || '' === $baseUri) {
            return 'http://localhost:3000';
        }

        return $baseUri;
    }
}
