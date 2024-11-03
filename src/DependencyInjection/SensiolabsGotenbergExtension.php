<?php

namespace Sensiolabs\GotenbergBundle\DependencyInjection;

use Sensiolabs\GotenbergBundle\Builder\Pdf\PdfBuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\ScreenshotBuilderInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\Routing\RequestContext;

/**
 * @phpstan-type WebhookDefinition array{url?: string, route?: array{0: string, 1: array<string, mixed>}, method?: 'POST'|'PUT'|'PATCH'|null}
 */
class SensiolabsGotenbergExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();

        /** @var array{base_uri: string, http_client: string, controller_listener: bool, request_context?: array{base_uri?: string}, assets_directory: string, webhook: array<string, array{success: WebhookDefinition, error?: WebhookDefinition}>, default_options: array{pdf: array{html: array<string, mixed>, url: array<string, mixed>, markdown: array<string, mixed>, office: array<string, mixed>, merge: array<string, mixed>, convert: array<string, mixed>}, screenshot: array{html: array<string, mixed>, url: array<string, mixed>, markdown: array<string, mixed>}, webhook?: string}} $config */
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new PhpFileLoader($container, new FileLocator(__DIR__.'/../../config'));
        $loader->load('builder_pdf.php');
        $loader->load('builder_screenshot.php');
        $loader->load('services.php');

        if (false === $config['controller_listener']) {
            $container->removeDefinition('sensiolabs_gotenberg.http_kernel.stream_builder');
        }

        if ($container->getParameter('kernel.debug') === true) {
            $loader->load('debug.php');
            $container->getDefinition('sensiolabs_gotenberg.data_collector')
                ->replaceArgument(3, [
                    'html' => $this->cleanUserOptions($config['default_options']['pdf']['html']),
                    'url' => $this->cleanUserOptions($config['default_options']['pdf']['url']),
                    'markdown' => $this->cleanUserOptions($config['default_options']['pdf']['markdown']),
                    'office' => $this->cleanUserOptions($config['default_options']['pdf']['office']),
                    'merge' => $this->cleanUserOptions($config['default_options']['pdf']['merge']),
                    'convert' => $this->cleanUserOptions($config['default_options']['pdf']['convert']),
                ])
            ;
        }

        $container->registerForAutoconfiguration(PdfBuilderInterface::class)
            ->addTag('sensiolabs_gotenberg.pdf_builder')
        ;

        $container->registerForAutoconfiguration(ScreenshotBuilderInterface::class)
            ->addTag('sensiolabs_gotenberg.screenshot_builder')
        ;

        $container->setAlias('sensiolabs_gotenberg.http_client', new Alias($config['http_client'], false));

        $baseUri = $config['request_context']['base_uri'] ?? null;
        $defaultWebhookConfig = $config['default_options']['webhook'] ?? null;

        if (null !== $baseUri) {
            $requestContextDefinition = new Definition(RequestContext::class);
            $requestContextDefinition->setFactory([RequestContext::class, 'fromUri']);
            $requestContextDefinition->setArguments([$baseUri]);

            $container->setDefinition('.sensiolabs_gotenberg.request_context', $requestContextDefinition);
        }

        foreach ($config['webhook'] as $name => $configuration) {
            $container->getDefinition('sensiolabs_gotenberg.webhook_configuration_registry')
                ->addMethodCall('add', [$name, $configuration]);
        }

        $this->processDefaultOptions('.sensiolabs_gotenberg.pdf_builder.html', $container, $config['default_options']['pdf']['html'], $defaultWebhookConfig);

        $this->processDefaultOptions('.sensiolabs_gotenberg.pdf_builder.url', $container, $config['default_options']['pdf']['url'], $defaultWebhookConfig);

        $this->processDefaultOptions('.sensiolabs_gotenberg.pdf_builder.markdown', $container, $config['default_options']['pdf']['markdown'], $defaultWebhookConfig);

        $this->processDefaultOptions('.sensiolabs_gotenberg.pdf_builder.office', $container, $config['default_options']['pdf']['office'], $defaultWebhookConfig);

        $this->processDefaultOptions('.sensiolabs_gotenberg.pdf_builder.merge', $container, $config['default_options']['pdf']['merge'], $defaultWebhookConfig);

        $this->processDefaultOptions('.sensiolabs_gotenberg.pdf_builder.convert', $container, $config['default_options']['pdf']['convert'], $defaultWebhookConfig);

        $this->processDefaultOptions('.sensiolabs_gotenberg.screenshot_builder.html', $container, $config['default_options']['screenshot']['html'], $defaultWebhookConfig);

        $this->processDefaultOptions('.sensiolabs_gotenberg.screenshot_builder.url', $container, $config['default_options']['screenshot']['url'], $defaultWebhookConfig);

        $this->processDefaultOptions('.sensiolabs_gotenberg.screenshot_builder.markdown', $container, $config['default_options']['screenshot']['markdown'], $defaultWebhookConfig);

        $definition = $container->getDefinition('sensiolabs_gotenberg.asset.base_dir_formatter');
        $definition->replaceArgument(2, $config['assets_directory']);
    }

    /**
     * @param array<string, mixed> $userConfigurations
     *
     * @return array<string, mixed>
     */
    private function cleanUserOptions(array $userConfigurations): array
    {
        return array_filter($userConfigurations, static function ($config, $configName): bool {
            return null !== $config && 'webhook' !== $configName;
        }, \ARRAY_FILTER_USE_BOTH);
    }

    /**
     * @param array<string, mixed> $config
     */
    private function processDefaultOptions(string $serviceId, ContainerBuilder $container, array $config, string|null $defaultWebhookName): void
    {
        $definition = $container->getDefinition($serviceId);
        $definition->addMethodCall('setConfigurations', [$this->cleanUserOptions($config)]);

        $webhookConfig = $config['webhook'] ?? null;
        if (null === $webhookConfig && null === $defaultWebhookName) {
            return;
        }

        if (null !== $defaultWebhookName) {
            $definition->addMethodCall('webhookConfiguration', [$defaultWebhookName], true);

            return;
        }

        if (\array_key_exists('config_name', $webhookConfig) && \is_string($webhookConfig['config_name'])) {
            $name = $webhookConfig['config_name'];
        } else {
            $name = $serviceId.'_webhook_config';
            $container->getDefinition('sensiolabs_gotenberg.webhook_configuration_registry')
                ->addMethodCall('add', [$name, $webhookConfig]);
        }
        $definition->addMethodCall('webhookConfiguration', [$name], true);
    }
}
