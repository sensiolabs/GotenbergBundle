<?php

namespace Sensiolabs\GotenbergBundle\DependencyInjection;

use Sensiolabs\GotenbergBundle\Enumeration\EmulatedMediaType;
use Sensiolabs\GotenbergBundle\Enumeration\PdfFormat;
use Sensiolabs\GotenbergBundle\Enumeration\ScreenshotFormat;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
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
                ->arrayNode('default_options')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('pdf')
                            ->addDefaultsIfNotSet()
                            ->append($this->addPdfHtmlNode())
                            ->append($this->addPdfUrlNode())
                            ->append($this->addPdfMarkdownNode())
                            ->append($this->addPdfOfficeNode())
                            ->append($this->addPdfMergeNode())
                            ->append($this->addPdfConvertNode())
                        ->end()
                        ->arrayNode('screenshot')
                            ->addDefaultsIfNotSet()
                            ->append($this->addScreenshotHtmlNode())
                            ->append($this->addScreenshotUrlNode())
                            ->append($this->addScreenshotMarkdownNode())
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }

    private function addPdfHtmlNode(): NodeDefinition
    {
        $treebuilder = new TreeBuilder('html');

        $treebuilder
            ->getRootNode()
            ->addDefaultsIfNotSet()
        ;

        $this->addChromiumPdfOptionsNode($treebuilder->getRootNode());

        return $treebuilder->getRootNode();
    }

    private function addPdfUrlNode(): NodeDefinition
    {
        $treebuilder = new TreeBuilder('url');

        $treebuilder
            ->getRootNode()
            ->addDefaultsIfNotSet()
        ;

        $this->addChromiumPdfOptionsNode($treebuilder->getRootNode());

        return $treebuilder->getRootNode();
    }

    private function addPdfMarkdownNode(): NodeDefinition
    {
        $treebuilder = new TreeBuilder('markdown');

        $treebuilder
            ->getRootNode()
            ->addDefaultsIfNotSet()
        ;

        $this->addChromiumPdfOptionsNode($treebuilder->getRootNode());

        return $treebuilder->getRootNode();
    }

    private function addScreenshotHtmlNode(): NodeDefinition
    {
        $treebuilder = new TreeBuilder('html');

        $treebuilder
            ->getRootNode()
            ->addDefaultsIfNotSet()
        ;

        $this->addChromiumScreenshotOptionsNode($treebuilder->getRootNode());

        return $treebuilder->getRootNode();
    }

    private function addScreenshotUrlNode(): NodeDefinition
    {
        $treebuilder = new TreeBuilder('url');

        $treebuilder
            ->getRootNode()
            ->addDefaultsIfNotSet()
        ;

        $this->addChromiumScreenshotOptionsNode($treebuilder->getRootNode());

        return $treebuilder->getRootNode();
    }

    private function addScreenshotMarkdownNode(): NodeDefinition
    {
        $treebuilder = new TreeBuilder('markdown');

        $treebuilder
            ->getRootNode()
            ->addDefaultsIfNotSet()
        ;

        $this->addChromiumScreenshotOptionsNode($treebuilder->getRootNode());

        return $treebuilder->getRootNode();
    }

    private function addChromiumPdfOptionsNode(ArrayNodeDefinition $parent): void
    {
        $parent
            ->children()
                ->arrayNode('header')
                    ->info('Add default header to the builder.')
                    ->children()
                        ->scalarNode('template')
                            ->info('Default header twig template to apply.')
                            ->defaultNull()
                        ->end()
                        ->scalarNode('context')
                            ->info('Default context for header twig template.')
                            ->defaultValue([])
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('footer')
                    ->info('Add default footer to the builder.')
                    ->children()
                        ->scalarNode('template')
                            ->info('Default footer twig template to apply.')
                            ->defaultNull()
                        ->end()
                        ->scalarNode('context')
                            ->info('Default context for footer twig template.')
                            ->defaultValue([])
                        ->end()
                    ->end()
                ->end()
                ->booleanNode('single_page')
                    ->info('Define whether to print the entire content in one single page. - default false. https://gotenberg.dev/docs/routes#page-properties-chromium')
                    ->defaultNull()
                ->end()
                ->floatNode('paper_width')
                    ->info('Paper width, in inches - default 8.5. https://gotenberg.dev/docs/routes#page-properties-chromium')
                    ->defaultNull()
                ->end()
                ->floatNode('paper_height')
                    ->info('Paper height, in inches - default 11. https://gotenberg.dev/docs/routes#page-properties-chromium')
                    ->defaultNull()
                ->end()
                ->scalarNode('margin_top')
                    ->info('Top margin, in inches - default 0.39. https://gotenberg.dev/docs/routes#page-properties-chromium')
                    ->defaultNull()
                ->end()
                ->scalarNode('margin_bottom')
                    ->info('Bottom margin, in inches - default 0.39. https://gotenberg.dev/docs/routes#page-properties-chromium')
                    ->defaultNull()
                ->end()
                ->scalarNode('margin_left')
                    ->info('Left margin, in inches - default 0.39. https://gotenberg.dev/docs/routes#page-properties-chromium')
                    ->defaultNull()
                ->end()
                ->scalarNode('margin_right')
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
                    ->values(array_map(static fn (EmulatedMediaType $case): string => $case->value, EmulatedMediaType::cases()))
                    ->defaultNull()
                ->end()
                ->arrayNode('cookies')
                    ->info('Cookies to store in the Chromium cookie jar - default None. https://gotenberg.dev/docs/routes#cookies-chromium')
                    ->defaultValue([])
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('name')->end()
                            ->scalarNode('value')->end()
                            ->scalarNode('domain')->end()
                            ->scalarNode('path')
                                ->defaultNull()
                            ->end()
                            ->booleanNode('secure')
                                ->defaultNull()
                            ->end()
                            ->booleanNode('httpOnly')
                                ->defaultNull()
                            ->end()
                            ->enumNode('sameSite')
                                ->info('Accepted values are "Strict", "Lax" or "None". https://gotenberg.dev/docs/routes#cookies-chromium')
                                ->values(['Strict', 'Lax', 'None'])
                                ->defaultNull()
                            ->end()
                        ->end()
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
                ->arrayNode('fail_on_http_status_codes')
                    ->info('Return a 409 Conflict response if the HTTP status code from the main page is not acceptable. - default [499,599]. https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium')
                    ->defaultValue([499, 599])
                    ->integerPrototype()
                    ->end()
                ->end()
                ->booleanNode('fail_on_console_exceptions')
                    ->info('Return a 409 Conflict response if there are exceptions in the Chromium console - default false. https://gotenberg.dev/docs/routes#console-exceptions')
                    ->defaultNull()
                ->end()
                ->booleanNode('skip_network_idle_event')
                    ->info('Do not wait for Chromium network to be idle. - default false. https://gotenberg.dev/docs/routes#performance-mode-chromium')
                    ->defaultNull()
                ->end()
                ->append($this->addPdfMetadata())
            ->end()
        ;

        $this->addPdfFormat($parent);
    }

    private function addChromiumScreenshotOptionsNode(ArrayNodeDefinition $parent): void
    {
        $parent
            ->children()
                ->integerNode('width')
                    ->info('The device screen width in pixels. - default 800. https://gotenberg.dev/docs/routes#screenshots-route')
                    ->defaultNull()
                ->end()
                ->integerNode('height')
                    ->info('The device screen height in pixels. - default 600. https://gotenberg.dev/docs/routes#screenshots-route')
                    ->defaultNull()
                ->end()
                ->booleanNode('clip')
                    ->info('Define whether to clip the screenshot according to the device dimensions - default false. https://gotenberg.dev/docs/routes#screenshots-route')
                    ->defaultNull()
                ->end()
                ->enumNode('format')
                    ->info('The image compression format, either "png", "jpeg" or "webp" - default png. https://gotenberg.dev/docs/routes#screenshots-route')
                    ->values(array_map(static fn (ScreenshotFormat $case): string => $case->value, ScreenshotFormat::cases()))
                    ->defaultNull()
                ->end()
                ->integerNode('quality')
                    ->info('The compression quality from range 0 to 100 (jpeg only) - default 100. https://gotenberg.dev/docs/routes#screenshots-route')
                    ->min(0)
                    ->max(100)
                    ->defaultNull()
                ->end()
                ->booleanNode('omit_background')
                    ->info('Hide the default white background and allow generating PDFs with transparency - default false. https://gotenberg.dev/docs/routes#page-properties-chromium')
                    ->defaultNull()
                ->end()
                ->booleanNode('optimize_for_speed')
                    ->info('Define whether to optimize image encoding for speed, not for resulting size. - default false. https://gotenberg.dev/docs/routes#page-properties-chromium')
                    ->defaultNull()
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
                    ->values(array_map(static fn (EmulatedMediaType $case): string => $case->value, EmulatedMediaType::cases()))
                    ->defaultNull()
                ->end()
                ->arrayNode('cookies')
                    ->info('Cookies to store in the Chromium cookie jar - default None. https://gotenberg.dev/docs/routes#cookies-chromium')
                    ->defaultValue([])
                        ->arrayPrototype()
                        ->children()
                            ->scalarNode('name')->end()
                            ->scalarNode('value')->end()
                            ->scalarNode('domain')->end()
                            ->scalarNode('path')
                                ->defaultNull()
                            ->end()
                            ->booleanNode('secure')
                                ->defaultNull()
                            ->end()
                            ->booleanNode('httpOnly')
                                ->defaultNull()
                            ->end()
                            ->enumNode('sameSite')
                                ->info('Accepted values are "Strict", "Lax" or "None". https://gotenberg.dev/docs/routes#cookies-chromium')
                                ->values(['Strict', 'Lax', 'None'])
                                ->defaultNull()
                            ->end()
                        ->end()
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
                ->arrayNode('fail_on_http_status_codes')
                    ->info('Return a 409 Conflict response if the HTTP status code from the main page is not acceptable. - default [499,599]. https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium')
                    ->defaultValue([499, 599])
                    ->integerPrototype()
                    ->end()
                ->end()
                ->booleanNode('fail_on_console_exceptions')
                    ->info('Return a 409 Conflict response if there are exceptions in the Chromium console - default false. https://gotenberg.dev/docs/routes#console-exceptions')
                    ->defaultNull()
                ->end()
                ->booleanNode('skip_network_idle_event')
                    ->info('Do not wait for Chromium network to be idle. - default false. https://gotenberg.dev/docs/routes#performance-mode-chromium')
                    ->defaultNull()
                ->end()
            ->end()
        ;
    }

    private function addPdfOfficeNode(): NodeDefinition
    {
        $treeBuilder = new TreeBuilder('office');

        $treeBuilder->getRootNode()
            ->addDefaultsIfNotSet()
            ->children()
                ->booleanNode('landscape')
                    ->info('The paper orientation to landscape - default false. https://gotenberg.dev/docs/routes#page-properties-chromium')
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
                ->booleanNode('export_form_fields')
                    ->info('Set whether to export the form fields or to use the inputted/selected content of the fields. - default true. https://gotenberg.dev/docs/routes#page-properties-libreoffice')
                    ->defaultNull()
                ->end()
                ->booleanNode('single_page_sheets')
                    ->info('Set whether to render the entire spreadsheet as a single page. - default false. https://gotenberg.dev/docs/routes#page-properties-libreoffice')
                    ->defaultNull()
                ->end()
                ->booleanNode('merge')
                    ->info('Merge alphanumerically the resulting PDFs. - default false. https://gotenberg.dev/docs/routes#merge-libreoffice')
                    ->defaultNull()
                ->end()
                ->append($this->addPdfMetadata())
            ->end()
        ;

        $this->addPdfFormat($treeBuilder->getRootNode());

        return $treeBuilder->getRootNode();
    }

    private function addPdfConvertNode(): NodeDefinition
    {
        $treeBuilder = new TreeBuilder('convert');
        $this->addPdfFormat($treeBuilder->getRootNode());

        return $treeBuilder->getRootNode();
    }

    private function addPdfMergeNode(): NodeDefinition
    {
        $treeBuilder = new TreeBuilder('merge');
        $this->addPdfFormat($treeBuilder->getRootNode());
        $treeBuilder->getRootNode()
            ->append($this->addPdfMetadata())
        ->end();

        return $treeBuilder->getRootNode();
    }

    private function addPdfFormat(ArrayNodeDefinition $parent): void
    {
        $parent
            ->addDefaultsIfNotSet()
            ->children()
                ->enumNode('pdf_format')
                    ->info('Convert PDF into the given PDF/A format - default None.')
                    ->values(array_map(static fn (PdfFormat $case): string => $case->value, PdfFormat::cases()))
                    ->defaultNull()
                ->end()
                ->booleanNode('pdf_universal_access')
                    ->info('Enable PDF for Universal Access for optimal accessibility - default false.')
                    ->defaultNull()
                ->end()
            ->end()
        ;
    }

    private function addPdfMetadata(): NodeDefinition
    {
        $treeBuilder = new TreeBuilder('metadata');

        return $treeBuilder->getRootNode()
            ->info('The metadata to write. Not all metadata are writable. Consider taking a look at https://exiftool.org/TagNames/XMP.html#pdf for an (exhaustive?) list of available metadata.')
            ->children()
                ->scalarNode('Author')->end()
                ->scalarNode('Copyright')->end()
                ->scalarNode('CreationDate')->end()
                ->scalarNode('Creator')->end()
                ->scalarNode('Keywords')->end()
                ->booleanNode('Marked')->end()
                ->scalarNode('ModDate')->end()
                ->scalarNode('PDFVersion')->end()
                ->scalarNode('Producer')->end()
                ->scalarNode('Subject')->end()
                ->scalarNode('Title')->end()
                ->enumNode('Trapped')->values(['True', 'False', 'Unknown'])->end()
            ->end()
        ;
    }
}
