<?php

namespace Sensiolabs\GotenbergBundle\Tests\Configurator;

use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\HtmlScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Configurator\BuilderConfigurator;
use Sensiolabs\GotenbergBundle\DependencyInjection\BuilderStack;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Sensiolabs\GotenbergBundle\Tests\CollectDeprecationsTrait;
use Sensiolabs\GotenbergBundle\Twig\GotenbergRuntime;
use Symfony\Component\DependencyInjection\Container;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\RuntimeLoader\RuntimeLoaderInterface;

final class BuilderConfiguratorTest extends TestCase
{
    use CollectDeprecationsTrait;

    private const FIXTURE_DIR = __DIR__.'/../Fixtures';

    /**
     * The "header" and "footer" keys of default_options.screenshot.* survive the deprecation of
     * the matching methods: #[WithConfigurationNode] still maps them.
     */
    public function testScreenshotHeaderAndFooterAreStillMappedToTheirMethod(): void
    {
        $builderStack = new BuilderStack();
        $builderStack->push(HtmlScreenshotBuilder::class);

        $mapping = $builderStack->getConfigMapping()[HtmlScreenshotBuilder::class];

        self::assertSame('header', $mapping['header']['method']);
        self::assertSame('footer', $mapping['footer']['method']);
    }

    /**
     * And since configuring them ends up calling those very methods, a YAML user gets the same
     * deprecation as someone calling header() by hand.
     */
    public function testScreenshotHeaderAndFooterConfigurationTriggersTheMethodDeprecations(): void
    {
        $mapping = [
            'header' => ['method' => 'header', 'mustUseVariadic' => true, 'callback' => null],
            'footer' => ['method' => 'footer', 'mustUseVariadic' => true, 'callback' => null],
        ];

        // Guard against drift: this literal must stay what BuilderStack actually derives from
        // the #[WithConfigurationNode] attributes. The literal is only there because the real
        // mapping types its callbacks too loosely for the constructor.
        $builderStack = new BuilderStack();
        $builderStack->push(HtmlScreenshotBuilder::class);
        $derived = $builderStack->getConfigMapping()[HtmlScreenshotBuilder::class];
        self::assertSame($mapping['header'], $derived['header']);
        self::assertSame($mapping['footer'], $derived['footer']);

        $configurator = new BuilderConfigurator([HtmlScreenshotBuilder::class => $mapping], [
            HtmlScreenshotBuilder::class => [
                'header' => ['template' => 'templates/header.html.twig', 'context' => ['name' => 'John Doe']],
                'footer' => ['template' => 'templates/footer.html.twig', 'context' => ['name' => 'John Doe']],
            ],
        ]);

        $builder = new HtmlScreenshotBuilder();
        $builder->setContainer($this->createContainer());

        $deprecations = $this->collectDeprecations(static function () use ($configurator, $builder): void {
            $configurator($builder);
        });

        self::assertSame([
            'Since sensiolabs/gotenberg-bundle 1.5: Calling "Sensiolabs\GotenbergBundle\Builder\Screenshot\HtmlScreenshotBuilder::header()" is deprecated, Gotenberg does not read headers on screenshot routes. It will be removed in 2.0.',
            'Since sensiolabs/gotenberg-bundle 1.5: Calling "Sensiolabs\GotenbergBundle\Builder\Screenshot\HtmlScreenshotBuilder::footer()" is deprecated, Gotenberg does not read footers on screenshot routes. It will be removed in 2.0.',
        ], $deprecations);
    }

    private function createContainer(): Container
    {
        $twig = new Environment(new FilesystemLoader(self::FIXTURE_DIR), [
            'strict_variables' => true,
        ]);

        $twig->addRuntimeLoader(new class implements RuntimeLoaderInterface {
            public function load(string $class): object|null
            {
                return GotenbergRuntime::class === $class ? new GotenbergRuntime() : null;
            }
        });

        $container = new Container();
        $container->set('twig', $twig);
        $container->set('asset_base_dir_formatter', new AssetBaseDirFormatter(self::FIXTURE_DIR, [self::FIXTURE_DIR]));

        return $container;
    }
}
