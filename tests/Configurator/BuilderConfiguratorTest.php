<?php

namespace Sensiolabs\GotenbergBundle\Tests\Configurator;

use Sensiolabs\GotenbergBundle\Builder\Pdf\UrlPdfBuilder;
use Sensiolabs\GotenbergBundle\Configurator\BuilderConfigurator;
use Sensiolabs\GotenbergBundle\DependencyInjection\BuilderStack;
use Sensiolabs\GotenbergBundle\Test\Builder\GotenbergBuilderTestCase;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

/**
 * The mapping the extension dumps into the container is the very one the configurator consumes. Building it with
 * BuilderStack and feeding it straight into BuilderConfigurator makes both declared shapes meet in analysed code,
 * so they cannot drift apart again without PHPStan noticing.
 *
 * @extends GotenbergBuilderTestCase<UrlPdfBuilder>
 */
final class BuilderConfiguratorTest extends GotenbergBuilderTestCase
{
    protected function createBuilder(): UrlPdfBuilder
    {
        $this->container->set('router', new UrlGenerator(new RouteCollection(), new RequestContext()));

        return (new UrlPdfBuilder())->url('https://example.com');
    }

    public function testEnumConfigurationValuesAreConvertedThroughTheirEnumClass(): void
    {
        $this->configure([
            'pdf_format' => 'PDF/A-1b',
            'emulated_media_type' => 'screen',
        ]);

        $this->getBuilder()->generate();

        $this->assertGotenbergFormData('pdfa', 'PDF/A-1b');
        $this->assertGotenbergFormData('emulatedMediaType', 'screen');
    }

    public function testUnitConfigurationValuesAreParsedAndSpreadOverTheMethodArguments(): void
    {
        $this->configure([
            'paper_width' => '21cm',
            'margin_top' => 4.5,
        ]);

        $this->getBuilder()->generate();

        $this->assertGotenbergFormData('paperWidth', '21cm');
        $this->assertGotenbergFormData('marginTop', '4.5in');
    }

    /**
     * @param array<string, mixed> $values
     */
    private function configure(array $values): void
    {
        $builderStack = new BuilderStack();
        $builderStack->push(UrlPdfBuilder::class);

        $configurator = new BuilderConfigurator($builderStack->getConfigMapping(), [UrlPdfBuilder::class => $values]);
        $configurator($this->getBuilder());
    }
}
