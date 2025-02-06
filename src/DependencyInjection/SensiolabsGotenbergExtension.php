<?php

namespace Sensiolabs\GotenbergBundle\DependencyInjection;

use Sensiolabs\GotenbergBundle\Builder\Behaviors\WebhookTrait;
use Sensiolabs\GotenbergBundle\BuilderOld\Pdf\PdfBuilderInterface;
use Sensiolabs\GotenbergBundle\BuilderOld\Screenshot\ScreenshotBuilderInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\Routing\RequestContext;

/**
 * @phpstan-import-type webhookConfiguration from WebhookTrait
 * @phpstan-type SensiolabsGotenbergConfiguration array{
 *      assets_directory: string,
 *      http_client?: string,
 *      request_context?: array{base_uri?: string},
 *      controller_listener: bool,
 *      webhook: webhookConfiguration,
 *      default_options: array{
 *          webhook?: string,
 *          pdf: array{
 *              html: array<string, mixed>,
 *              url: array<string, mixed>,
 *              markdown: array<string, mixed>,
 *              office: array<string, mixed>,
 *              merge: array<string, mixed>,
 *              convert: array<string, mixed>,
 *              split: array<string, mixed>
 *          },
 *          screenshot: array{
 *              html: array<string, mixed>,
 *              url: array<string, mixed>,
 *              markdown: array<string, mixed>
 *          }
 *      }
 *  }
 */
class SensiolabsGotenbergExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();

        /** @var SensiolabsGotenbergConfiguration $config
         */
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

        // Asset base dir formatter
        $container
            ->getDefinition('.sensiolabs_gotenberg.asset.base_dir_formatter')
            ->replaceArgument(1, $config['assets_directory'])
        ;

        //		$loader->load('builder_pdf.php');
        //        $loader->load('builder_screenshot.php');
        //        $loader->load('services.php');
        //
        //        if (false === $config['controller_listener']) {
        //            $container->removeDefinition('sensiolabs_gotenberg.http_kernel.stream_builder');
        //        }
        //
        if ($container->getParameter('kernel.debug') === true) {
            $loader->load('debug.php');
            $container->getDefinition('sensiolabs_gotenberg.data_collector')
                ->replaceArgument(3, [
                    'html' => $this->cleanUserOptions($config['default_options']['pdf']['html']),
                    //                    'url' => $this->cleanUserOptions($config['default_options']['pdf']['url']),
                    //                    'markdown' => $this->cleanUserOptions($config['default_options']['pdf']['markdown']),
                    //                    'office' => $this->cleanUserOptions($config['default_options']['pdf']['office']),
                    'merge' => $this->cleanUserOptions($config['default_options']['pdf']['merge']),
                    //                    'convert' => $this->cleanUserOptions($config['default_options']['pdf']['convert']),
                    //                    'split' => $this->cleanUserOptions($config['default_options']['pdf']['split']),
                ])
            ;
        }
        //
        //        $container->registerForAutoconfiguration(PdfBuilderInterface::class)
        //            ->addTag('sensiolabs_gotenberg.pdf_builder')
        //        ;
        //
        //        $container->registerForAutoconfiguration(ScreenshotBuilderInterface::class)
        //            ->addTag('sensiolabs_gotenberg.screenshot_builder')
        //        ;
        //
        //        $container->setAlias('sensiolabs_gotenberg.http_client', new Alias($config['http_client'], false));
        //
        //        $baseUri = $config['request_context']['base_uri'] ?? null;
        //
        //        if (null !== $baseUri) {
        //            $requestContextDefinition = new Definition(RequestContext::class);
        //            $requestContextDefinition->setFactory([RequestContext::class, 'fromUri']);
        //            $requestContextDefinition->setArguments([$baseUri]);
        //
        //            $container->setDefinition('.sensiolabs_gotenberg.request_context', $requestContextDefinition);
        //        }
        //
        //        foreach ($config['webhook'] as $name => $configuration) {
        //            $container->getDefinition('.sensiolabs_gotenberg.webhook_configuration_registry')
        //                ->addMethodCall('add', [$name, $configuration]);
        //        }
        //
        //        $this->processDefaultOptions($container, $config, '.sensiolabs_gotenberg.pdf_builder.html', $config['default_options']['pdf']['html']);
        //
        //        $this->processDefaultOptions($container, $config, '.sensiolabs_gotenberg.pdf_builder.url', $config['default_options']['pdf']['url']);
        //
        //        $this->processDefaultOptions($container, $config, '.sensiolabs_gotenberg.pdf_builder.markdown', $config['default_options']['pdf']['markdown']);
        //
        //        $this->processDefaultOptions($container, $config, '.sensiolabs_gotenberg.pdf_builder.office', $config['default_options']['pdf']['office']);
        //
        //        $this->processDefaultOptions($container, $config, '.sensiolabs_gotenberg.pdf_builder.merge', $config['default_options']['pdf']['merge']);
        //
        //        $this->processDefaultOptions($container, $config, '.sensiolabs_gotenberg.pdf_builder.convert', $config['default_options']['pdf']['convert']);
        //
        //        $this->processDefaultOptions($container, $config, '.sensiolabs_gotenberg.pdf_builder.split', $config['default_options']['pdf']['split']);
        //
        //        $this->processDefaultOptions($container, $config, '.sensiolabs_gotenberg.screenshot_builder.html', $config['default_options']['screenshot']['html']);
        //
        //        $this->processDefaultOptions($container, $config, '.sensiolabs_gotenberg.screenshot_builder.url', $config['default_options']['screenshot']['url']);
        //
        //        $this->processDefaultOptions($container, $config, '.sensiolabs_gotenberg.screenshot_builder.markdown', $config['default_options']['screenshot']['markdown']);
        //
        //        $definition = $container->getDefinition('.sensiolabs_gotenberg.asset.base_dir_formatter');
        //        $definition->replaceArgument(1, $config['assets_directory']);

        // Configurators
        $container
            ->getDefinition('.sensiolabs_gotenberg.pdf_builder_configurator.html')
            ->replaceArgument(0, $this->processDefaultOptions($config, $config['default_options']['pdf']['html']))
        ;

        $container
            ->getDefinition('.sensiolabs_gotenberg.pdf_builder_configurator.merge')
            ->replaceArgument(0, $this->processDefaultOptions($config, $config['default_options']['pdf']['merge']))
        ;

        $container
            ->getDefinition('.sensiolabs_gotenberg.pdf_builder_configurator.office')
            ->replaceArgument(0, $this->processDefaultOptions($config, $config['default_options']['pdf']['office']))
        ;
    }

    /**
     * @param SensiolabsGotenbergConfiguration $config
     * @param array<string, mixed>             $serviceConfig
     */
    private function processDefaultOptions(array $config, array $serviceConfig): array
    {
        $serviceOptions = $this->processWebhookOptions($config['webhook'], $config['default_options']['webhook'] ?? null, $serviceConfig);

        return $this->cleanUserOptions($serviceOptions);
    }

    /**
     * @param webhookConfiguration $webhookConfig
     * @param array<string, mixed> $serviceConfig
     * @return array<string, mixed>
     */
    private function processWebhookOptions(array $webhookConfig, string|null $webhookDefaultConfigName, array $serviceConfig): array
    {
        $serviceWebhookConfig = $serviceConfig['webhook'] ?? [];
        $webhookConfigName = $serviceWebhookConfig['config_name'] ?? $webhookDefaultConfigName ?? null;

        $serviceConfig['webhook'] = array_merge($webhookConfig[$webhookConfigName] ?? [], $serviceWebhookConfig);

        return $serviceConfig;
    }

    /**
     * @param array<string, mixed> $userConfigurations
     *
     * @return array<string, mixed>
     */
    private function cleanUserOptions(array $userConfigurations): array
    {
        return array_filter($userConfigurations, static function ($config, $configName): bool {
            return null !== $config && [] !== $config;
        }, \ARRAY_FILTER_USE_BOTH);
    }
}
