<?php

namespace Sensiolabs\GotenbergBundle\DependencyInjection;

use Sensiolabs\GotenbergBundle\Builder\BuilderTrait;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * @phpstan-import-type ConfigOptions from BuilderTrait
 */
class SensiolabsGotenbergExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new PhpFileLoader($container, new FileLocator(__DIR__.'/../../config'));
        $loader->load('services.php');

        $configuration = new Configuration();

        /** @var array{base_uri: string, default_options: ConfigOptions} $config */
        $config = $this->processConfiguration($configuration, $configs);

        $definition = $container->getDefinition('sensiolabs_gotenberg.client');
        $definition->replaceArgument(0, $config['base_uri']);

        $definition = $container->getDefinition('sensiolabs_gotenberg');
        $definition->replaceArgument(1, $this->cleanDefaultOptions($config['default_options']));
    }

    /**
     * @param array<string, mixed> $userConfigurations
     * @return array<string, mixed>
     */
    private function cleanDefaultOptions(array $userConfigurations): array
    {
        return array_filter($userConfigurations, static function($config): bool {

            if (is_array($config)) {
                return 0 !== count($config);
            }

            return null !== $config ;
        });
    }
}
