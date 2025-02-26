<?php

namespace Sensiolabs\GotenbergBundle\DependencyInjection;

use Sensiolabs\GotenbergBundle\Builder\Behaviors\WebhookTrait;
use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\ServiceLocator;
use Symfony\Component\Routing\RequestContext;

/**
 * @phpstan-import-type WebhookConfiguration from WebhookTrait
 *
 * @phpstan-type SensiolabsGotenbergConfiguration array{
 *      assets_directory: string,
 *      http_client?: string,
 *      request_context?: array{base_uri?: string},
 *      controller_listener: bool,
 *      webhook: array<string, WebhookConfiguration>,
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
    private BuilderStack $builderStack;

    /**
     * @param class-string<BuilderInterface> $class
     */
    public function registerBuilder(string $class): void
    {
        $this->builderStack->push($class);
    }

    public function setBuilderStack(BuilderStack $builderStack): void
    {
        $this->builderStack = $builderStack;
    }

    /**
     * @param array<array-key, SensiolabsGotenbergConfiguration> $config
     */
    public function getConfiguration(array $config, ContainerBuilder $container): Configuration
    {
        return new Configuration($this->builderStack->getConfigNode());
    }

    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = $this->getConfiguration($configs, $container);

        /*
         * @var SensiolabsGotenbergConfiguration $config
         */
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new PhpFileLoader($container, new FileLocator(__DIR__.'/../../config'));

        // Services
        $loader->load('services.php');

        // Builders
        $loader->load('builder.php');
        $loader->load('builder_pdf.php');
        $loader->load('builder_screenshot.php');

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

        if (false === $config['controller_listener']) {
            $container->removeDefinition('sensiolabs_gotenberg.http_kernel.stream_builder');
        }

        if ($container->getParameter('kernel.debug') === true) {
            $loader->load('debug.php');
            $container->getDefinition('sensiolabs_gotenberg.data_collector')
                ->replaceArgument(3, [
                    'html' => $this->processDefaultOptions($config, $config['default_options']['pdf']['html']),
                    'url' => $this->processDefaultOptions($config, $config['default_options']['pdf']['url']),
                    'markdown' => $this->processDefaultOptions($config, $config['default_options']['pdf']['markdown']),
                    'office' => $this->processDefaultOptions($config, $config['default_options']['pdf']['office']),
                    'merge' => $this->processDefaultOptions($config, $config['default_options']['pdf']['merge']),
                    'convert' => $this->processDefaultOptions($config, $config['default_options']['pdf']['convert']),
                    'split' => $this->processDefaultOptions($config, $config['default_options']['pdf']['split']),
                ])
            ;
        }

        $container->registerForAutoconfiguration(BuilderInterface::class)
            ->addTag('sensiolabs_gotenberg.builder')
        ;

        // Configurators
        $configValueMapping = [];
        foreach ($config['default_options'] as $type => $buildersOptions) {
            if ('webhook' === $type) {
                continue;
            }

            foreach ($buildersOptions as $builderName => $builderOptions) {
                $class = $this->builderStack->getTypeReverseMapping()[$type][$builderName];
                $configValueMapping[$class] = $this->processDefaultOptions($config, $builderOptions);
            }
        }

        $container->getDefinition('sensiolabs_gotenberg.builder_configurator')
            ->replaceArgument(0, $this->builderStack->getConfigMapping())
            ->replaceArgument(1, $configValueMapping)
        ;
    }

    /**
     * @param array<string, mixed> $config
     * @param array<string, mixed> $serviceConfig
     *
     * @return array<string, mixed>
     */
    private function processDefaultOptions(array $config, array $serviceConfig): array
    {
        $serviceOptions = $this->processWebhookOptions($config['webhook'], $config['default_options']['webhook'] ?? null, $serviceConfig);

        return $this->cleanUserOptions($serviceOptions);
    }

    /**
     * @param array<string, WebhookConfiguration> $webhookConfig
     * @param array<string, mixed>                $serviceConfig
     *
     * @return array<string, mixed>
     */
    private function processWebhookOptions(array $webhookConfig, string|null $webhookDefaultConfigName, array $serviceConfig): array
    {
        $serviceWebhookConfig = [];
        $serviceWebhookConfigName = null;
        if (isset($serviceConfig['webhook'])) {
            if (\is_array($serviceConfig['webhook'])) {
                $serviceWebhookConfig = $serviceConfig['webhook'];

                /** @var string|null $serviceWebhookConfigName */
                $serviceWebhookConfigName = $serviceConfig['webhook']['config_name'] ?? null;
            }

            if (\is_string($serviceConfig['webhook'])) {
                $serviceWebhookConfigName = $serviceConfig['webhook'];
            }
        }

        $webhookConfigName = $serviceWebhookConfigName ?? $webhookDefaultConfigName;
        $defaultConfig = $webhookConfig[$webhookConfigName] ?? [];

        $serviceConfig['webhook'] = array_merge($defaultConfig, $serviceWebhookConfig);

        return $serviceConfig;
    }

    /**
     * @param array<string, mixed> $userConfigurations
     *
     * @return array<string, mixed>
     */
    private function cleanUserOptions(array $userConfigurations): array
    {
        foreach ($userConfigurations as $key => $value) {
            if (\is_array($value)) {
                $userConfigurations[$key] = $this->cleanUserOptions($value);

                if ([] === $userConfigurations[$key]) {
                    unset($userConfigurations[$key]);
                }
            } elseif (null === $value) {
                unset($userConfigurations[$key]);
            }
        }

        return $userConfigurations;
    }
}
