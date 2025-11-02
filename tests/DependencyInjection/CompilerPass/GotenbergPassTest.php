<?php

namespace Sensiolabs\GotenbergBundle\Tests\DependencyInjection\CompilerPass;

use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Builder\Pdf\HtmlPdfBuilder;
use Sensiolabs\GotenbergBundle\DependencyInjection\CompilerPass\GotenbergPass;
use Sensiolabs\GotenbergBundle\DependencyInjection\SensiolabsGotenbergExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class GotenbergPassTest extends TestCase
{
    private const BUILDER = HtmlPdfBuilder::class;

    private function getContainerBuilder(bool $withDataCollector = false): ContainerBuilder
    {
        $container = new ContainerBuilder();

        if (true === $withDataCollector) {
            $dataCollector = new Definition();

            $container->setDefinition('sensiolabs_gotenberg.data_collector', $dataCollector);
        }

        $htmlPdfBuilderService = new Definition(self::BUILDER);
        $htmlPdfBuilderService->addTag('sensiolabs_gotenberg.builder');
        $container->setDefinition('.sensiolabs_gotenberg.pdf_builder.html', $htmlPdfBuilderService);

        $someRandomService = new Definition(\stdClass::class);
        $container->setDefinition('.service.random', $someRandomService);

        return $container;
    }

    private function getExtension(): SensiolabsGotenbergExtension
    {
        $extension = new SensiolabsGotenbergExtension();
        $extension->registerBuilder(self::BUILDER);

        return $extension;
    }

    public function testItDoesNothingIfDataCollectorNotRegistered(): void
    {
        $container = $this->getContainerBuilder();
        $container->registerExtension($this->getExtension());

        $serviceIds = $container->getServiceIds();

        self::assertNotContains('sensiolabs_gotenberg.data_collector', $serviceIds);

        $compilerPass = new GotenbergPass();
        $compilerPass->process($container);

        self::assertSame($serviceIds, $container->getServiceIds());
    }
}
