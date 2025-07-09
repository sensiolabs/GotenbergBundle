<?php

namespace Sensiolabs\GotenbergBundle\DependencyInjection;

use Sensiolabs\GotenbergBundle\Builder\AbstractBuilder;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\WebhookTrait;
use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
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

        /**
         * @var SensiolabsGotenbergConfiguration $config
         */
        $config = $this->processConfiguration($configuration, $configs);
        $defaultConfiguration = $this->processDefaultConfiguration($config);

        $loader = new PhpFileLoader($container, new FileLocator(__DIR__.'/../../config'));

        // Services
        $loader->load('services.php');

        // Builders
        $loader->load('builder.php');
        $loader->load('builder_pdf.php');
        $loader->load('builder_screenshot.php');

        // HTTP Client
        $container->setAlias('sensiolabs_gotenberg.http_client', new Alias($defaultConfiguration['http_client'] ?? 'http_client', false));

        // Request context
        $baseUri = $defaultConfiguration['request_context']['base_uri'] ?? null;
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
            ->replaceArgument(1, $defaultConfiguration['assets_directory'])
        ;

        if (false === $defaultConfiguration['controller_listener']) {
            $container->removeDefinition('sensiolabs_gotenberg.http_kernel.stream_builder');
        }

        if ($container->getParameter('kernel.debug') === true) {
            $loader->load('debug.php');
            $container->getDefinition('sensiolabs_gotenberg.data_collector')
                ->replaceArgument(5, $defaultConfiguration['default_options'])
            ;
        }

        $container->registerForAutoconfiguration(BuilderInterface::class)
            ->addTag('sensiolabs_gotenberg.builder')
        ;

        $container->registerForAutoconfiguration(AbstractBuilder::class);

        // Configurators
        $configValueMapping = [];
        foreach ($defaultConfiguration['default_options'] as $type => $buildersOptions) {
            if ('webhook' === $type) {
                continue;
            }

            foreach ($buildersOptions as $builderName => $builderOptions) {
                $class = $this->builderStack->getTypeReverseMapping()[$type][$builderName];
                $configValueMapping[$class] = $defaultConfiguration['default_options'][$type][$builderName];
            }
        }

        $container->getDefinition('sensiolabs_gotenberg.builder_configurator')
            ->replaceArgument(0, $this->builderStack->getConfigMapping())
            ->replaceArgument(1, $configValueMapping)
        ;
    }

    /**
     * @param array<string, mixed> $config
     *
     * @return array<string, mixed>
     */
    private function processDefaultConfiguration(array $config): array
    {
        foreach ($config['default_options'] as $type => $builders) {
            if ('webhook' === $type) {
                continue;
            }

            foreach ($builders as $builderName => $builderOptions) {
                $builderWebhookConfig = [];
                $builderWebhookConfigName = null;
                if (isset($builderOptions['webhook'])) {
                    if (\is_array($builderOptions['webhook'])) {
                        $builderWebhookConfig = $builderOptions['webhook'];

                        /** @var string|null $builderWebhookConfigName */
                        $builderWebhookConfigName = $builderOptions['webhook']['config_name'] ?? null;
                    }

                    if (\is_string($builderOptions['webhook'])) {
                        $builderWebhookConfigName = $builderOptions['webhook'];
                    }
                }

                $webhookConfigName = $builderWebhookConfigName ?? $config['default_options']['webhook'] ?? null;
                $defaultWebhookConfig = $config['webhook'][$webhookConfigName] ?? [];

                $config['default_options'][$type][$builderName]['webhook'] = array_merge(
                    $this->cleanBuilderConfiguration($defaultWebhookConfig),
                    $this->cleanBuilderConfiguration($builderWebhookConfig),
                );

                $config['default_options'][$type][$builderName] = $this->cleanBuilderConfiguration($config['default_options'][$type][$builderName]);
            }
        }

        return $config;
    }

    /**
     * @param array<string, mixed> $userConfigurations
     *
     * @return array<string, mixed>
     */
    private function cleanBuilderConfiguration(array $userConfigurations): array
    {
        foreach ($userConfigurations as $key => $value) {
            if (\is_array($value)) {
                $userConfigurations[$key] = $this->cleanBuilderConfiguration($value);

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
