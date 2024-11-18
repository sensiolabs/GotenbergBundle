<?php

namespace Sensiolabs\GotenbergBundle\DependencyInjection;

use Sensiolabs\GotenbergBundle\BuilderOld\Pdf\PdfBuilderInterface;
use Sensiolabs\GotenbergBundle\BuilderOld\Screenshot\ScreenshotBuilderInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\Routing\RequestContext;

class SensiolabsGotenbergExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();

        /** @var array{base_uri: string, http_client: string|null, request_context?: array{base_uri?: string}, assets_directory: string, default_options: array{pdf: array{html: array<string, mixed>, url: array<string, mixed>, markdown: array<string, mixed>, office: array<string, mixed>, merge: array<string, mixed>, convert: array<string, mixed>}, screenshot: array{html: array<string, mixed>, url: array<string, mixed>, markdown: array<string, mixed>}}} $config */
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new PhpFileLoader($container, new FileLocator(__DIR__.'/../../config'));

        // Services
        $loader->load('services.php');

        // Builders
        $loader->load('builder.php');
        $loader->load('builder_pdf.php');
        $loader->load('builder_screenshot.php');
        $container
            ->registerForAutoconfiguration(PdfBuilderInterface::class)
            ->addTag('sensiolabs_gotenberg.pdf_builder')
        ;
        $container
            ->registerForAutoconfiguration(ScreenshotBuilderInterface::class)
            ->addTag('sensiolabs_gotenberg.screenshot_builder')
        ;

        // Configurators
        $container
            ->getDefinition('.sensiolabs_gotenberg.pdf_builder_configurator.html')
            ->replaceArgument(0, $this->cleanUserOptions($config['default_options']['pdf']['html']))
        ;
        $container
            ->getDefinition('.sensiolabs_gotenberg.pdf_builder_configurator.merge')
            ->replaceArgument(0, $this->cleanUserOptions($config['default_options']['pdf']['merge']))
        ;
        $container
            ->getDefinition('.sensiolabs_gotenberg.screenshot_builder_configurator.html')
            ->replaceArgument(0, $this->cleanUserOptions($config['default_options']['screenshot']['html']))
        ;

        // HTTP Client
        $container->setAlias('sensiolabs_gotenberg.http_client', new Alias($config['http_client'] ?? 'http_client', false));

        // Request context
        $baseUri = $config['request_context']['base_uri'] ?? null;
        if (null !== $baseUri) {
            $container
                ->register('.sensiolabs_gotenberg.request_context', RequestContext::class)
                ->setFactory([RequestContext::class, 'fromUri'])
                ->setArguments([$baseUri])
            ;
        }

        //        $definition = $container->getDefinition('.sensiolabs_gotenberg.pdf_builder.html');
        //        $definition->addMethodCall('setConfigurations', [$this->cleanUserOptions($config['default_options']['pdf']['html'])]);
        //
        //        $definition = $container->getDefinition('.sensiolabs_gotenberg.pdf_builder.url');
        //        $definition->addMethodCall('setConfigurations', [$this->cleanUserOptions($config['default_options']['pdf']['url'])]);
        //
        //        $definition = $container->getDefinition('.sensiolabs_gotenberg.pdf_builder.markdown');
        //        $definition->addMethodCall('setConfigurations', [$this->cleanUserOptions($config['default_options']['pdf']['markdown'])]);
        //
        //        $definition = $container->getDefinition('.sensiolabs_gotenberg.pdf_builder.office');
        //        $definition->addMethodCall('setConfigurations', [$this->cleanUserOptions($config['default_options']['pdf']['office'])]);
        //
        //        $definition = $container->getDefinition('.sensiolabs_gotenberg.pdf_builder.merge');
        //        $definition->addMethodCall('setConfigurations', [$this->cleanUserOptions($config['default_options']['pdf']['merge'])]);
        //
        //        $definition = $container->getDefinition('.sensiolabs_gotenberg.pdf_builder.convert');
        //        $definition->addMethodCall('setConfigurations', [$this->cleanUserOptions($config['default_options']['pdf']['convert'])]);
        //
        //        $definition = $container->getDefinition('.sensiolabs_gotenberg.screenshot_builder.html');
        //        $definition->addMethodCall('setConfigurations', [$this->cleanUserOptions($config['default_options']['screenshot']['html'])]);
        //
        //        $definition = $container->getDefinition('.sensiolabs_gotenberg.screenshot_builder.url');
        //        $definition->addMethodCall('setConfigurations', [$this->cleanUserOptions($config['default_options']['screenshot']['url'])]);
        //
        //        $definition = $container->getDefinition('.sensiolabs_gotenberg.screenshot_builder.markdown');
        //        $definition->addMethodCall('setConfigurations', [$this->cleanUserOptions($config['default_options']['screenshot']['markdown'])]);

        // Asset base dir formatter
        $container
            ->getDefinition('sensiolabs_gotenberg.asset.base_dir_formatter')
            ->replaceArgument(2, $config['assets_directory'])
        ;

        // Debug
        if ($container->getParameter('kernel.debug') === true) {
            $loader->load('debug.php');
            $container
                ->getDefinition('sensiolabs_gotenberg.data_collector')
                ->replaceArgument(3, array_map($this->cleanUserOptions(...), $config['default_options']))
            ;
        }
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
            return null !== $config;
        });
    }
}
