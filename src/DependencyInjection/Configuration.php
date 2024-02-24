<?php

namespace Sensiolabs\GotenbergBundle\DependencyInjection;

use Sensiolabs\GotenbergBundle\Enum\EmulatedMediaType;
use Sensiolabs\GotenbergBundle\Enum\PdfFormat;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('sensiolabs_gotenberg');

        $treeBuilder->getRootNode()
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('base_uri')
                    ->info('Host of your local Gotenberg API')
                    ->defaultValue('http://localhost:3000')
                    ->cannotBeEmpty()
                    ->validate()
                        ->ifTrue(static function ($option): bool {
                            return preg_match('/^(http|https):\/\//', $option) !== 1;
                        })
                        ->thenInvalid('Invalid API Gotenberg host.')
                    ->end()
                ->end()
                ->scalarNode('asset_base_dir')
                    ->info('Base DIR where assets are located')
                    ->defaultValue('%kernel.project_dir%/public/')
                    ->cannotBeEmpty()
                ->end()
                ->arrayNode('default_options')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->floatNode('paper_width')
                            ->info('Paper width, in inches - default 8.5. https://gotenberg.dev/docs/routes#page-properties-chromium')
                            ->defaultNull()
                            ->end()
                        ->floatNode('paper_height')
                            ->info('Paper height, in inches - default 11. https://gotenberg.dev/docs/routes#page-properties-chromium')
                            ->defaultNull()
                            ->end()
                        ->floatNode('margin_top')
                            ->info('Top margin, in inches - default 0.39. https://gotenberg.dev/docs/routes#page-properties-chromium')
                            ->defaultNull()
                            ->end()
                        ->floatNode('margin_bottom')
                            ->info('Bottom margin, in inches - default 0.39. https://gotenberg.dev/docs/routes#page-properties-chromium')
                            ->defaultNull()
                            ->end()
                        ->floatNode('margin_left')
                            ->info('Left margin, in inches - default 0.39. https://gotenberg.dev/docs/routes#page-properties-chromium')
                            ->defaultNull()
                            ->end()
                        ->floatNode('margin_right')
                            ->info('Right margin, in inches - default 0.39. https://gotenberg.dev/docs/routes#page-properties-chromium')
                            ->defaultNull()
                            ->end()
                        ->booleanNode('prefer_css_page_size')
                            ->info('Define whether to prefer page size as defined by CSS - default false. https://gotenberg.dev/docs/routes#page-properties-chromium')
                            ->defaultNull()
                            ->end()
                        ->booleanNode('print_background')
                            ->info('Print the background graphics - default false. https://gotenberg.dev/docs/routes#page-properties-chromium')
                            ->defaultNull()
                            ->end()
                        ->booleanNode('omit_background')
                            ->info('Hide the default white background and allow generating PDFs with transparency - default false. https://gotenberg.dev/docs/routes#page-properties-chromium')
                            ->defaultNull()
                            ->end()
                        ->booleanNode('landscape')
                            ->info('The paper orientation to landscape - default false. https://gotenberg.dev/docs/routes#page-properties-chromium')
                            ->defaultNull()
                            ->end()
                        ->floatNode('scale')
                            ->info('The scale of the page rendering (e.g., 1.0) - default 1.0. https://gotenberg.dev/docs/routes#page-properties-chromium')
                            ->defaultNull()
                            ->end()
                        ->scalarNode('native_page_ranges')
                            ->info('Page ranges to print, e.g., "1-5, 8, 11-13" - default All pages. https://gotenberg.dev/docs/routes#page-properties-chromium')
                            ->defaultNull()
                            ->validate()
                                ->ifTrue(static function ($option): bool {
                                    return preg_match('/([\d]+[-][\d]+)/', $option) !== 1;
                                })
                                ->thenInvalid('Invalid range values, the range value format need to look like e.g 1-20.')
                            ->end()
                        ->end()
                        ->scalarNode('wait_delay')
                            ->info('Duration (e.g, "5s") to wait when loading an HTML document before converting it into PDF - default None. https://gotenberg.dev/docs/routes#wait-before-rendering')
                            ->defaultNull()
                            ->validate()
                                ->ifTrue(static function ($option): bool {
                                    return !\is_string($option);
                                })
                                ->thenInvalid('Invalid value %s')
                            ->end()
                        ->end()
                        ->scalarNode('wait_for_expression')
                            ->info('The JavaScript expression to wait before converting an HTML document into PDF until it returns true - default None. https://gotenberg.dev/docs/routes#wait-before-rendering')
                            ->defaultNull()
                            ->validate()
                                ->ifTrue(static function ($option) {
                                    return !\is_string($option);
                                })
                                ->thenInvalid('Invalid value %s')
                            ->end()
                        ->end()
                        ->enumNode('emulated_media_type')
                            ->info('The media type to emulate, either "screen" or "print" - default "print". https://gotenberg.dev/docs/routes#emulated-media-type')
                            ->values([EmulatedMediaType::Screen->value, EmulatedMediaType::Print->value])
                            ->defaultNull()
                        ->end()
                        ->scalarNode('user_agent')
                            ->info('Override the default User-Agent header - default None. https://gotenberg.dev/docs/routes#custom-http-headers')
                            ->defaultNull()
                            ->validate()
                                ->ifTrue(static function ($option) {
                                    return !\is_string($option);
                                })
                                ->thenInvalid('Invalid value %s')
                            ->end()
                        ->end()
                        ->arrayNode('extra_http_headers')
                            ->info('HTTP headers to send by Chromium while loading the HTML document - default None. https://gotenberg.dev/docs/routes#custom-http-headers')
                            ->defaultValue([])
                            ->useAttributeAsKey('name')
                            ->arrayPrototype()
                                ->children()
                                    ->scalarNode('name')
                                        ->validate()
                                            ->ifTrue(static function ($option) {
                                                return !\is_string($option);
                                            })
                                            ->thenInvalid('Invalid header name %s')
                                        ->end()
                                    ->end()
                                    ->scalarNode('value')
                                        ->validate()
                                            ->ifTrue(static function ($option) {
                                                return !\is_string($option);
                                            })
                                            ->thenInvalid('Invalid header value %s')
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->booleanNode('fail_on_console_exceptions')
                            ->info('Return a 409 Conflict response if there are exceptions in the Chromium console - default false. https://gotenberg.dev/docs/routes#console-exceptions')
                            ->defaultNull()
                        ->end()
                        ->enumNode('pdf_format')
                            ->info('Convert the resulting PDF into the given PDF/A format - default None. https://gotenberg.dev/docs/routes#pdfa-chromium')
                            ->values([PdfFormat::Pdf1a->value, PdfFormat::Pdf2b->value, PdfFormat::Pdf3b->value])
                            ->defaultNull()
                        ->end()
                        ->booleanNode('pdf_universal_access')
                            ->info('Enable PDF for Universal Access for optimal accessibility - default false. https://gotenberg.dev/docs/routes#console-exceptions')
                            ->defaultNull()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
