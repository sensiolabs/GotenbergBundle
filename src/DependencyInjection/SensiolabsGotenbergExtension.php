<?php

namespace Sensiolabs\GotenbergBundle\DependencyInjection;

use LogicException;
use Sensiolabs\GotenbergBundle\Builder\Attributes\ExposeSemantic;
use Sensiolabs\GotenbergBundle\Builder\Attributes\SemanticNode;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\WebhookTrait;
use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\BuilderOld\Pdf\PdfBuilderInterface;
use Sensiolabs\GotenbergBundle\BuilderOld\Screenshot\ScreenshotBuilderInterface;
use Symfony\Bundle\SecurityBundle\DependencyInjection\MainConfiguration;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\Routing\RequestContext;
use function array_reverse;
use function is_a;
use function sprintf;

/**
 * @phpstan-import-type webhookConfiguration from WebhookTrait
 *
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
    /**
     * @var array<string, class-string<BuilderInterface>>
     */
    private array $typeReverseMapping = [];

    /**
     * @var array<class-string<BuilderInterface>, array<string, string>>
     */
    private array $configMapping = [];

    private array $configNode = [];

    /**
     * @param 'pdf'|'screenshot' $type
     * @param class-string<BuilderInterface> $class
     */
    public function registerBuilder(string $type, string $class): void
    {
        if (!is_a($class, BuilderInterface::class, true)) {
            throw new LogicException('logic');
        }

        $reflection = new \ReflectionClass($class);
        $nodeAttributes = $reflection->getAttributes(SemanticNode::class);

        if (count($nodeAttributes) === 0) {
            throw new LogicException(sprintf('%s is missing the %s attribute', $class, SemanticNode::class));
        }

        /** @var SemanticNode $semanticNode */
        $semanticNode = $nodeAttributes[0]->newInstance();

        $this->typeReverseMapping[$semanticNode->name] = $class;

        $treeBuilder = new TreeBuilder($semanticNode->name);
        $root = $treeBuilder->getRootNode()->addDefaultsIfNotSet();

        foreach (array_reverse($reflection->getMethods(\ReflectionMethod::IS_PUBLIC)) as $method) {
            $attributes = $method->getAttributes(ExposeSemantic::class);
            if (\count($attributes) === 0) {
                continue;
            }

            /** @var ExposeSemantic $attribute */
            $attribute = $attributes[0]->newInstance();

            $root->append($attribute->node->create());

            $this->configMapping[$class] ??= [];
            $this->configMapping[$class][$attribute->node->getName()] = $method->getName();
        }

        $this->configNode[$type] ??= [];
        $this->configNode[$type][$class] = $root;
    }

    public function getConfiguration(array $config, ContainerBuilder $container): Configuration
    {
        return new Configuration($this->configNode);
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
                    'url' => $this->cleanUserOptions($config['default_options']['pdf']['url']),
                    //                    'markdown' => $this->cleanUserOptions($config['default_options']['pdf']['markdown']),
                    'office' => $this->cleanUserOptions($config['default_options']['pdf']['office']),
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
        $configValueMapping = [];
        foreach ($config['default_options'] as $builders) {
            foreach ($builders as $builder => $options) {
                $class = $this->typeReverseMapping[$builder];
                $configValueMapping[$class] = $options;
            }
        }

        $container->getDefinition('sensiolabs_gotenberg.builder_configurator')
            ->replaceArgument(0, $this->configMapping)
            ->replaceArgument(1, $configValueMapping)
        ;
    }

    /**
     * @param SensiolabsGotenbergConfiguration $config
     * @param array<string, mixed>             $serviceConfig
     *
     * @return array<string, mixed>
     */
    private function processDefaultOptions(array $config, array $serviceConfig): array
    {
        $serviceOptions = $this->processWebhookOptions($config['webhook'], $config['default_options']['webhook'] ?? null, $serviceConfig);

        return $this->cleanUserOptions($serviceOptions);
    }

    /**
     * @param webhookConfiguration $webhookConfig
     * @param array<string, mixed> $serviceConfig
     *
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
