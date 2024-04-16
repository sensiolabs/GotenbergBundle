<?php

namespace Sensiolabs\GotenbergBundle\DependencyInjection;

use Sensiolabs\GotenbergBundle\Builder\PdfBuilderInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class SensiolabsGotenbergExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();

        /** @var array{base_uri: string, http_client: string|null, base_directory: string, default_options: array{html: array<string, mixed>, url: array<string, mixed>, markdown: array<string, mixed>, office: array<string, mixed>}} $config */
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new PhpFileLoader($container, new FileLocator(__DIR__.'/../../config'));
        $loader->load('services.php');

        if ($container->getParameter('kernel.debug') === true) {
            $loader->load('debug.php');
            $container->getDefinition('sensiolabs_gotenberg.data_collector')
                ->replaceArgument(2, [
                    'html' => $this->cleanUserOptions($config['default_options']['html']),
                    'url' => $this->cleanUserOptions($config['default_options']['url']),
                    'markdown' => $this->cleanUserOptions($config['default_options']['markdown']),
                    'office' => $this->cleanUserOptions($config['default_options']['office']),
                ])
            ;
        }

        $container->registerForAutoconfiguration(PdfBuilderInterface::class)
            ->addTag('sensiolabs_gotenberg.builder')
        ;

        $container->setAlias('sensiolabs_gotenberg.http_client', new Alias($config['http_client'] ?? 'http_client', false));

        $definition = $container->getDefinition('sensiolabs_gotenberg.client');
        $definition->replaceArgument(0, $config['base_uri']);

        $definition = $container->getDefinition('.sensiolabs_gotenberg.builder.html');
        $definition->addMethodCall('setConfigurations', [$this->cleanUserOptions($config['default_options']['html'])]);

        $definition = $container->getDefinition('.sensiolabs_gotenberg.builder.url');
        $definition->addMethodCall('setConfigurations', [$this->cleanUserOptions($config['default_options']['url'])]);

        $definition = $container->getDefinition('.sensiolabs_gotenberg.builder.markdown');
        $definition->addMethodCall('setConfigurations', [$this->cleanUserOptions($config['default_options']['markdown'])]);

        $definition = $container->getDefinition('.sensiolabs_gotenberg.builder.office');
        $definition->addMethodCall('setConfigurations', [$this->cleanUserOptions($config['default_options']['office'])]);

        $definition = $container->getDefinition('sensiolabs_gotenberg.asset.base_dir_formatter');
        $definition->replaceArgument(2, $config['base_directory']);
    }

    /**
     * @template T of array<string, mixed>
     *
     * @param T $userConfigurations
     *
     * @return array<key-of<T>, value-of<T>>
     */
    private function cleanUserOptions(array $userConfigurations): array
    {
        return array_filter($userConfigurations, static function ($config): bool {
            if (\is_array($config)) {
                return 0 !== \count($config);
            }

            return null !== $config;
        });
    }
}
