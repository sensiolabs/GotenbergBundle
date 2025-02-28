<?php

namespace Sensiolabs\GotenbergBundle;

use Sensiolabs\GotenbergBundle\Builder\Pdf\ConvertPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\FlattenPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\HtmlPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\LibreOfficePdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\MarkdownPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\MergePdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\SplitPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\UrlPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\HtmlScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\MarkdownScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\UrlScreenshotBuilder;
use Sensiolabs\GotenbergBundle\DependencyInjection\BuilderStack;
use Sensiolabs\GotenbergBundle\DependencyInjection\CompilerPass\GotenbergPass;
use Sensiolabs\GotenbergBundle\DependencyInjection\SensiolabsGotenbergExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SensiolabsGotenbergBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

    public function build(ContainerBuilder $container): void
    {
        $builderStack = new BuilderStack();

        /** @var SensiolabsGotenbergExtension $extension */
        $extension = $container->getExtension('sensiolabs_gotenberg');
        $extension->setBuilderStack($builderStack);

        $extension->registerBuilder(ConvertPdfBuilder::class);
        $extension->registerBuilder(FlattenPdfBuilder::class);
        $extension->registerBuilder(HtmlPdfBuilder::class);
        $extension->registerBuilder(LibreOfficePdfBuilder::class);
        $extension->registerBuilder(MarkdownPdfBuilder::class);
        $extension->registerBuilder(MergePdfBuilder::class);
        $extension->registerBuilder(SplitPdfBuilder::class);
        $extension->registerBuilder(UrlPdfBuilder::class);

        $extension->registerBuilder(HtmlScreenshotBuilder::class);
        $extension->registerBuilder(MarkdownScreenshotBuilder::class);
        $extension->registerBuilder(UrlScreenshotBuilder::class);

        $container->addCompilerPass(new GotenbergPass($builderStack));
    }
}
