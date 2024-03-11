<?php

namespace Sensiolabs\GotenbergBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class SensiolabsGotenbergExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new PhpFileLoader($container, new FileLocator(__DIR__.'/../../config'));
        $loader->load('services.php');

        $configuration = new Configuration();

        /** @var array{base_uri: string, base_directory: string, default_options: array{html: array<string, mixed>, url: array<string, mixed>, markdown: array<string, mixed>, office: array<string, mixed>}} $config */
        $config = $this->processConfiguration($configuration, $configs);

        $definition = $container->getDefinition('sensiolabs_gotenberg.client');
        $definition->replaceArgument(0, $config['base_uri']);

        $definition = $container->getDefinition('sensiolabs_gotenberg');
        $definition->replaceArgument(1, $this->cleanUserOptions($config['default_options']['html']));
        $definition->replaceArgument(2, $this->cleanUserOptions($config['default_options']['url']));
        $definition->replaceArgument(3, $this->cleanUserOptions($config['default_options']['markdown']));
        $definition->replaceArgument(4, $this->cleanUserOptions($config['default_options']['office']));

        $definition = $container->getDefinition('sensiolabs_gotenberg.asset.base_dir_formatter');
        $definition->replaceArgument(2, $config['base_directory']);
    }

    /**
     * @param array<string, mixed> $userConfigurations
     *
     * @return array<string, mixed>
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
