<?php

namespace Sensiolabs\GotenbergBundle\Tests;

use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\DependencyInjection\CompilerPass\GotenbergPass;
use Sensiolabs\GotenbergBundle\SensiolabsGotenbergBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SensiolabsGotenbergBundleTest extends TestCase
{
    public function testCanBeAutoDiscovered(): void
    {
        $bundle = new SensiolabsGotenbergBundle();
        $reflection = new \ReflectionClass($bundle);

        self::assertSame(\dirname($reflection->getFileName() ?: '', 2), $bundle->getPath());
    }

    private function getContainerBuilder(): ContainerBuilder
    {
        return new ContainerBuilder();
    }

    public function testCompilerPassesAreAllSet(): void
    {
        $bundle = new SensiolabsGotenbergBundle();
        $container = $this->getContainerBuilder();

        $originalCompilerPasses = $container->getCompilerPassConfig()->getPasses();

        $extension = $bundle->getContainerExtension();
        self::assertNotNull($extension);

        $container->registerExtension($extension);
        $bundle->build($container);

        $currentCompilerPasses = $container->getCompilerPassConfig()->getPasses();

        self::assertNotEquals($originalCompilerPasses, $currentCompilerPasses);
        $toClass = static fn (object $object): string => $object::class;

        $newPasses = array_diff(
            array_map($toClass, $currentCompilerPasses),
            array_map($toClass, $originalCompilerPasses),
        );

        self::assertContains(GotenbergPass::class, $newPasses);
    }
}
