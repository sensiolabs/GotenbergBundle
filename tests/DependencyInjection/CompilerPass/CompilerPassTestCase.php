<?php

namespace Sensiolabs\GotenbergBundle\Tests\DependencyInjection\CompilerPass;

use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Builder\Pdf\HtmlPdfBuilder;
use Sensiolabs\GotenbergBundle\DependencyInjection\BuilderStack;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

abstract class CompilerPassTestCase extends TestCase
{
    private const BUILDER = HtmlPdfBuilder::class;

    protected function getContainerBuilder(bool $withDataCollector = false): ContainerBuilder
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

    protected function getBuilderStack(): BuilderStack
    {
        $builderStack = new BuilderStack();
        $builderStack->push(self::BUILDER);

        return $builderStack;
    }
}
