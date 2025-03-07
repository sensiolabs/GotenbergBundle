<?php

namespace Sensiolabs\GotenbergBundle\Tests\DependencyInjection\CompilerPass;

use Sensiolabs\GotenbergBundle\Debug\Builder\TraceableBuilder;
use Sensiolabs\GotenbergBundle\DependencyInjection\CompilerPass\GotenbergPass;
use Sensiolabs\GotenbergBundle\DependencyInjection\CompilerPass\TraceablePass;
use Symfony\Component\DependencyInjection\Reference;

final class TraceablePassTest extends CompilerPassTestCase
{
    public function testItDecoratesPdfBuildersWithTraceableWhenDataCollectorIsPresent(): void
    {
        $container = $this->getContainerBuilder(withDataCollector: true);

        $serviceIds = $container->getServiceIds();

        self::assertContains('sensiolabs_gotenberg.data_collector', $serviceIds);

        $compilerPass = new GotenbergPass($this->getBuilderStack());
        $compilerPass->process($container);

        $traceablePass = new TraceablePass();
        $traceablePass->process($container);

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
