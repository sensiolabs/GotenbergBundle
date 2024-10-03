<?php

namespace Sensiolabs\GotenbergBundle\DependencyInjection;

use Sensiolabs\GotenbergBundle\NodeBuilder\NodeBuilderInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @param array<string, array<string, array<array-key, NodeBuilderInterface>>> $builders
     */
    public function __construct(
        private readonly array $builders,
    ) {
    }

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('sensiolabs_gotenberg');

        $treeBuilder->getRootNode()
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('assets_directory')
                    ->info('Base directory will be used for assets, files, markdown')
                    ->defaultValue('%kernel.project_dir%/assets')
                ->end()
                ->scalarNode('http_client')
                    ->info('HTTP Client reference to use. (Must have a base_uri)')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->arrayNode('request_context')
                    ->info('Override the request Gotenberg will make to call one of your routes.')
                    ->children()
                        ->scalarNode('base_uri')
                            ->info('Used only when using `->route()`. Overrides the guessed `base_url` from the request. May be useful in CLI.')
                        ->end()
                    ->end()
                ->end()
                ->booleanNode('controller_listener')
                    ->defaultTrue()
                    ->info('Enables the listener on kernel.view to stream GotenbergFileResult object.')
                ->end()
                ->append($this->addNamedWebhookDefinition())
                ->append($this->addDefaultOptionsNode())
            ->end()
        ;

        return $treeBuilder;
    }

    private function addDefaultOptionsNode(): NodeDefinition
    {
        $defaultOptionsTreeBuilder = new TreeBuilder('default_options');
        $defaultOptionsTreeBuilder->getRootNode()
            ->addDefaultsIfNotSet()
        ;

        $webhookNode = (new TreeBuilder('webhook', 'scalar'))
            ->getRootNode()
            ->info('Webhook configuration name.')
        ;

        $defaultOptionsTreeBuilder->getRootNode()->append($webhookNode);

        foreach ($this->builders as $type => $innerBuilders) {
            $typeTreeBuilder = new TreeBuilder($type);
            $typeTreeBuilder->getRootNode()
                ->addDefaultsIfNotSet()
            ;

            foreach ($innerBuilders as $builderType => $builderNodes) {
                $builderTypeTreeBuilder = new TreeBuilder($builderType);
                $builderTypeTreeBuilder->getRootNode()
                    ->addDefaultsIfNotSet()
                ;
                foreach ($builderNodes as $node) {
                    $builderTypeTreeBuilder->getRootNode()->append($node->create());
                }

                $typeTreeBuilder->getRootNode()->append($builderTypeTreeBuilder->getRootNode());
            }

            $defaultOptionsTreeBuilder->getRootNode()->append($typeTreeBuilder->getRootNode());
        }

        return $defaultOptionsTreeBuilder->getRootNode();
    }

    private function addNamedWebhookDefinition(): NodeDefinition
    {
        $treeBuilder = new TreeBuilder('webhook');

        return $treeBuilder->getRootNode()
                ->defaultValue([])
                ->useAttributeAsKey('name')
                ->arrayPrototype()
                    ->children()
                        ->scalarNode('name')
                            ->validate()
                                ->ifTrue(static function (mixed $option): bool {
                                    return !\is_string($option);
                                })
                                ->thenInvalid('Invalid webhook configuration name %s')
                            ->end()
                        ->end()
                        ->append($this->addWebhookConfigurationNode('success'))
                        ->append($this->addWebhookConfigurationNode('error'))
                        ->append($this->addExtraHttpHeadersNode())
                    ->end()
                    ->validate()
                        ->ifTrue(static function (mixed $option): bool {
                            return !isset($option['success']);
                        })
                        ->thenInvalid('Invalid webhook configuration : At least a "success" key is required.')
                    ->end()
                ->end();
    }

    private function addWebhookConfigurationNode(string $name): NodeDefinition
    {
        $treeBuilder = new TreeBuilder($name);

        return $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('url')
                    ->info('The URL to call.')
                    ->example('https://webhook.site/#!/view/{some-token}')
                ->end()
                ->variableNode('route')
                    ->info('Route configuration.')
                    ->beforeNormalization()
                        ->ifArray()
                            ->then(function (array $v): array {
                                return [$v[0], $v[1] ?? []];
                            })
                        ->ifString()
                            ->then(function (string $v): array {
                                return [$v, []];
                            })
                    ->end()
                    ->validate()
                        ->ifTrue(function ($v): bool {
                            return !\is_array($v) || \count($v) !== 2 || !\is_string($v[0]) || !\is_array($v[1]);
                        })
                        ->thenInvalid('The "route" parameter must be a string or an array containing a string and an array.')
                    ->end()
                    ->example("['my_route', ['param1' => 'value1', 'param2' => 'value2']]")
                ->end()
                ->enumNode('method')
                    ->info('HTTP method to use on that endpoint.')
                    ->values(['POST', 'PUT', 'PATCH'])
                    ->defaultNull()
                ->end()
            ->end()
        ;
    }

    private function addExtraHttpHeadersNode(): NodeDefinition
    {
        $treeBuilder = new TreeBuilder('extra_http_headers');

        return $treeBuilder->getRootNode()
            ->info('HTTP headers to send by Chromium while loading the HTML document - default None. https://gotenberg.dev/docs/routes#custom-http-headers')
            ->defaultValue([])
            ->normalizeKeys(false)
            ->useAttributeAsKey('name')
            ->variablePrototype()
            ->end()
        ;
    }
}
