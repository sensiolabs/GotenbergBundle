<?php

namespace Sensiolabs\GotenbergBundle\Tests\DependencyInjection\CompilerPass;

use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Builder\Pdf\HtmlPdfBuilder;
use Sensiolabs\GotenbergBundle\Debug\Builder\TraceableBuilder;
use Sensiolabs\GotenbergBundle\DependencyInjection\BuilderStack;
use Sensiolabs\GotenbergBundle\DependencyInjection\CompilerPass\GotenbergPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

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

    private function getBuilderStack(): BuilderStack
    {
        $builderStack = new BuilderStack();
        $builderStack->push(self::BUILDER);

        return $builderStack;
    }

    public function testItDoesNothingIfDataCollectorNotRegistered(): void
    {
        $container = $this->getContainerBuilder();

        $serviceIds = $container->getServiceIds();

        self::assertNotContains('sensiolabs_gotenberg.data_collector', $serviceIds);

        $compilerPass = new GotenbergPass($this->getBuilderStack());
        $compilerPass->process($container);

        self::assertSame($serviceIds, $container->getServiceIds());
    }

    public function testItDecoratesPdfBuildersWithTraceableWhenDataCollectorIsPresent(): void
    {
        $container = $this->getContainerBuilder(withDataCollector: true);

        $serviceIds = $container->getServiceIds();

        self::assertContains('sensiolabs_gotenberg.data_collector', $serviceIds);

        $compilerPass = new GotenbergPass($this->getBuilderStack());
        $compilerPass->process($container);

        $newServiceIds = $container->getServiceIds();
        self::assertNotSame($serviceIds, $newServiceIds);
        self::assertContains('.sensiolabs_gotenberg.pdf_builder.html', $newServiceIds);
        self::assertContains('.debug.sensiolabs_gotenberg.pdf_builder.html', $newServiceIds);
        self::assertNotContains('.debug.service.random', $newServiceIds);

        $traceablePdfTaggedService = $container->getDefinition('.debug.sensiolabs_gotenberg.pdf_builder.html');
        self::assertNotNull($traceablePdfTaggedService);
        self::assertSame(TraceableBuilder::class, $traceablePdfTaggedService->getClass());

        $traceablePdfTaggedServiceArguments = $traceablePdfTaggedService->getArguments();
        self::assertCount(2, $traceablePdfTaggedServiceArguments);

        self::assertArrayHasKey('$inner', $traceablePdfTaggedServiceArguments);
        self::assertInstanceOf(Reference::class, $traceablePdfTaggedServiceArguments['$inner']);
        self::assertSame('.inner', (string) $traceablePdfTaggedServiceArguments['$inner']);

        self::assertArrayHasKey('$stopwatch', $traceablePdfTaggedServiceArguments);
        self::assertInstanceOf(Reference::class, $traceablePdfTaggedServiceArguments['$stopwatch']);
        self::assertSame('debug.stopwatch', (string) $traceablePdfTaggedServiceArguments['$stopwatch']);
    }
}
