<?php

namespace Sensiolabs\GotenbergBundle\DependencyInjection;

use Sensiolabs\GotenbergBundle\Builder\Pdf\PdfBuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\ScreenshotBuilderInterface;
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

        /** @var array{base_uri: string, http_client: string|null, base_directory: string, default_options: array{pdf: array{html: array<string, mixed>, url: array<string, mixed>, markdown: array<string, mixed>, office: array<string, mixed>}, screenshot: array{html: array<string, mixed>, url: array<string, mixed>, markdown: array<string, mixed>}}} $config */
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new PhpFileLoader($container, new FileLocator(__DIR__.'/../../config'));
        $loader->load('services.php');

        if ($container->getParameter('kernel.debug') === true) {
            $loader->load('debug.php');
            $container->getDefinition('sensiolabs_gotenberg.data_collector')
                ->replaceArgument(2, [
                    'html' => $this->cleanUserOptions($config['default_options']['pdf']['html']),
                    'url' => $this->cleanUserOptions($config['default_options']['pdf']['url']),
                    'markdown' => $this->cleanUserOptions($config['default_options']['pdf']['markdown']),
                    'office' => $this->cleanUserOptions($config['default_options']['pdf']['office']),
                ])
            ;
        }

        $container->registerForAutoconfiguration(PdfBuilderInterface::class)
            ->addTag('sensiolabs_gotenberg.pdf_builder')
        ;

        $container->registerForAutoconfiguration(ScreenshotBuilderInterface::class)
            ->addTag('sensiolabs_gotenberg.screenshot_builder')
        ;

        $container->setAlias('sensiolabs_gotenberg.http_client', new Alias($config['http_client'] ?? 'http_client', false));

        $definition = $container->getDefinition('sensiolabs_gotenberg.client');
        $definition->replaceArgument(0, $config['base_uri']);

        $definition = $container->getDefinition('.sensiolabs_gotenberg.pdf_builder.html');
        $definition->addMethodCall('setConfigurations', [$this->cleanUserOptions($config['default_options']['pdf']['html'])]);

        $definition = $container->getDefinition('.sensiolabs_gotenberg.pdf_builder.url');
        $definition->addMethodCall('setConfigurations', [$this->cleanUserOptions($config['default_options']['pdf']['url'])]);

        $definition = $container->getDefinition('.sensiolabs_gotenberg.pdf_builder.markdown');
        $definition->addMethodCall('setConfigurations', [$this->cleanUserOptions($config['default_options']['pdf']['markdown'])]);

        $definition = $container->getDefinition('.sensiolabs_gotenberg.pdf_builder.office');
        $definition->addMethodCall('setConfigurations', [$this->cleanUserOptions($config['default_options']['pdf']['office'])]);

        $definition = $container->getDefinition('.sensiolabs_gotenberg.screenshot_builder.html');
        $definition->addMethodCall('setConfigurations', [$this->cleanUserOptions($config['default_options']['screenshot']['html'])]);

        $definition = $container->getDefinition('.sensiolabs_gotenberg.screenshot_builder.url');
        $definition->addMethodCall('setConfigurations', [$this->cleanUserOptions($config['default_options']['screenshot']['url'])]);

        $definition = $container->getDefinition('.sensiolabs_gotenberg.screenshot_builder.markdown');
        $definition->addMethodCall('setConfigurations', [$this->cleanUserOptions($config['default_options']['screenshot']['markdown'])]);

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
