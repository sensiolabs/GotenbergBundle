<?php

declare(strict_types=1);

namespace Sensiolabs\GotenbergBundle\Tests\DependencyInjection\CompilerPass;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Builder\Pdf\PdfBuilderInterface;
use Sensiolabs\GotenbergBundle\Debug\Builder\TraceablePdfBuilder;
use Sensiolabs\GotenbergBundle\DependencyInjection\CompilerPass\GotenbergPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

#[CoversClass(GotenbergPass::class)]
class GotenbergPassTest extends TestCase
{
    private function getContainerBuilder(bool $withDataCollector = false): ContainerBuilder
    {
        $container = new ContainerBuilder();

        if (true === $withDataCollector) {
            $dataCollector = new Definition();

            $container->setDefinition('sensiolabs_gotenberg.data_collector', $dataCollector);
        }

        $pdfTaggedService = new Definition(PdfBuilderInterface::class);
        $pdfTaggedService->addTag('sensiolabs_gotenberg.pdf_builder');
        $container->setDefinition('.service.pdf_tagged', $pdfTaggedService);

        $someRandomService = new Definition(\stdClass::class);
        $container->setDefinition('.service.random', $someRandomService);

        return $container;
    }

    public function testItDoesNothingIfDataCollectorNotRegistered(): void
    {
        $container = $this->getContainerBuilder();

        $serviceIds = $container->getServiceIds();

        self::assertNotContains('sensiolabs_gotenberg.data_collector', $serviceIds);

        $compilerPass = new GotenbergPass();
        $compilerPass->process($container);

        self::assertSame($serviceIds, $container->getServiceIds());
    }

    public function testItDecoratesPdfBuildersWithTraceableWhenDataCollectorIsPresent(): void
    {
        $container = $this->getContainerBuilder(withDataCollector: true);

        $serviceIds = $container->getServiceIds();

        self::assertContains('sensiolabs_gotenberg.data_collector', $serviceIds);

        $compilerPass = new GotenbergPass();
        $compilerPass->process($container);

        $newServiceIds = $container->getServiceIds();
        self::assertNotSame($serviceIds, $newServiceIds);
        self::assertContains('.service.pdf_tagged', $newServiceIds);
        self::assertContains('.debug.service.pdf_tagged', $newServiceIds);
        self::assertNotContains('.debug.service.random', $newServiceIds);

        $traceablePdfTaggedService = $container->getDefinition('.debug.service.pdf_tagged');
        self::assertNotNull($traceablePdfTaggedService);
        self::assertSame(TraceablePdfBuilder::class, $traceablePdfTaggedService->getClass());

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
