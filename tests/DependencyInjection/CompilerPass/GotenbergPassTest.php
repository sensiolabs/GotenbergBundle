<?php

namespace Sensiolabs\GotenbergBundle\Tests\DependencyInjection\CompilerPass;

use Sensiolabs\GotenbergBundle\DependencyInjection\CompilerPass\GotenbergPass;

class GotenbergPassTest extends CompilerPassTestCase
{
    public function testItDoesNothingIfDataCollectorNotRegistered(): void
    {
        $container = $this->getContainerBuilder();

        $serviceIds = $container->getServiceIds();

        self::assertNotContains('sensiolabs_gotenberg.data_collector', $serviceIds);

        $compilerPass = new GotenbergPass($this->getBuilderStack());
        $compilerPass->process($container);

        self::assertSame($serviceIds, $container->getServiceIds());
    }
}
