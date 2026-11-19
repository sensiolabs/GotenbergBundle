<?php

namespace Sensiolabs\GotenbergBundle\Tests\Configurator;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Pdf\UrlPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\HtmlScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Configurator\BuilderConfigurator;
use Sensiolabs\GotenbergBundle\DependencyInjection\BuilderStack;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Sensiolabs\GotenbergBundle\Test\Builder\GotenbergBuilderTestCase;
use Sensiolabs\GotenbergBundle\Tests\CollectDeprecationsTrait;
use Sensiolabs\GotenbergBundle\Twig\GotenbergRuntime;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\TextPart;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\RuntimeLoader\RuntimeLoaderInterface;

/**
 * The mapping the extension dumps into the container is the very one the configurator consumes. Building it with
 * BuilderStack and feeding it straight into BuilderConfigurator makes both declared shapes meet in analysed code,
 * so they cannot drift apart again without PHPStan noticing.
 *
 * @extends GotenbergBuilderTestCase<UrlPdfBuilder>
 */
final class BuilderConfiguratorTest extends GotenbergBuilderTestCase
{
    use CollectDeprecationsTrait;

    protected function createBuilder(): UrlPdfBuilder
    {
        $this->container->set('router', new UrlGenerator(new RouteCollection(), new RequestContext()));

        return (new UrlPdfBuilder())->url('https://example.com');
    }

    public function testEnumConfigurationValuesAreConvertedThroughTheirEnumClass(): void
    {
        $this->configure($this->getBuilder(), [
            'pdf_format' => 'PDF/A-1b',
            'emulated_media_type' => 'screen',
        ]);

        $this->getBuilder()->generate();

        $this->assertGotenbergFormData('pdfa', 'PDF/A-1b');
        $this->assertGotenbergFormData('emulatedMediaType', 'screen');
    }

    public function testUnitConfigurationValuesAreParsedAndSpreadOverTheMethodArguments(): void
    {
        $this->configure($this->getBuilder(), [
            'paper_width' => '21cm',
            'margin_top' => 4.5,
        ]);

        $this->getBuilder()->generate();

        $this->assertGotenbergFormData('paperWidth', '21cm');
        $this->assertGotenbergFormData('marginTop', '4.5in');
    }

    public function testStampsAndWatermarksConfigurationValuesAreAddedInOrder(): void
    {
        $this->withGotenbergVersion('8.36.0');

        $this->configure($this->getBuilder(), [
            'stamps' => [
                ['source' => 'text', 'expression' => 'APPROVED'],
                ['source' => 'image', 'file' => 'assets/logo.png', 'pages' => '1'],
            ],
            'watermarks' => [
                ['source' => 'text', 'expression' => 'DRAFT', 'options' => ['opacity' => '0.5']],
                ['source' => 'text', 'expression' => 'CONFIDENTIAL'],
            ],
        ]);

        $this->getBuilder()->generate();

        self::assertSame(['text', 'image'], $this->getFormDataValues('stampSource'));
        self::assertSame(['APPROVED', 'logo.png'], $this->getFormDataValues('stampExpression'));
        self::assertSame(['', '1'], $this->getFormDataValues('stampPages'));
        self::assertSame(['logo.png'], $this->getFormDataValues('stamp'));
        self::assertSame(['text', 'text'], $this->getFormDataValues('watermarkSource'));
        self::assertSame(['DRAFT', 'CONFIDENTIAL'], $this->getFormDataValues('watermarkExpression'));
        self::assertSame(['{"opacity":"0.5"}', ''], $this->getFormDataValues('watermarkOptions'));
    }

    public function testDeprecatedStampKeysCannotBeCombinedWithTheListedStamps(): void
    {
        $this->expectException(InvalidBuilderConfiguration::class);
        $this->expectExceptionMessage('The deprecated stamp* methods cannot be combined with addStampText(), addStampImage(), addStampPdf() or stamps().');

        $this->configure($this->getBuilder(), [
            'stamp_source' => 'text',
            'stamp_expression' => 'APPROVED',
            'stamps' => [
                ['source' => 'image', 'file' => 'assets/logo.png'],
            ],
        ]);

        $this->getBuilder()->generate();
    }

    /**
     * @return list<string>
     */
    private function getFormDataValues(string $name): array
    {
        $values = [];
        /** @var TextPart|DataPart $part */
        foreach ($this->client->getBody() as $part) {
            if ($part->getName() === $name) {
                $values[] = $part instanceof DataPart && null !== $part->getFilename() ? $part->getFilename() : $part->getBody();
            }
        }

        return $values;
    }

    /**
     * The "header" and "footer" keys of default_options.screenshot.* survive the deprecation of the matching
     * methods: #[WithConfigurationNode] still maps them, so a YAML user gets the same deprecation as someone
     * calling header() by hand.
     */
    public function testScreenshotHeaderAndFooterConfigurationTriggersTheMethodDeprecations(): void
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
        $this->container->set('twig', $twig);

        $builder = new HtmlScreenshotBuilder();
        $builder->setContainer($this->container);

        $deprecations = $this->collectDeprecations(function () use ($builder): void {
            $this->configure($builder, [
                'footer' => ['template' => 'templates/footer.html.twig', 'context' => ['name' => 'John Doe']],
                'header' => ['template' => 'templates/header.html.twig', 'context' => ['name' => 'John Doe']],
            ]);
        });

        self::assertSame([
            'Since sensiolabs/gotenberg-bundle 1.5: Calling "Sensiolabs\GotenbergBundle\Builder\Screenshot\HtmlScreenshotBuilder::footer()" is deprecated, Gotenberg does not read footers on screenshot routes. It will be removed in 2.0.',
            'Since sensiolabs/gotenberg-bundle 1.5: Calling "Sensiolabs\GotenbergBundle\Builder\Screenshot\HtmlScreenshotBuilder::header()" is deprecated, Gotenberg does not read headers on screenshot routes. It will be removed in 2.0.',
        ], $deprecations);
    }

    /**
     * @param array<string, mixed> $values
     */
    private function configure(BuilderInterface $builder, array $values): void
    {
        $builderStack = new BuilderStack();
        $builderStack->push($builder::class);

        $configurator = new BuilderConfigurator($builderStack->getConfigMapping(), [$builder::class => $values]);
        $configurator($builder);
    }
}
