<?php

namespace Sensiolabs\GotenbergBundle;

use Sensiolabs\GotenbergBundle\Builder\Pdf\ConvertPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\EmbedPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\EncryptPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\FlattenPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\HtmlPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\LibreOfficePdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\MarkdownPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\MergePdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\SplitPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\StampPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\UrlPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\WatermarkPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\HtmlScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\MarkdownScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\UrlScreenshotBuilder;
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

    protected function createContainerExtension(): SensiolabsGotenbergExtension
    {
        $extension = new SensiolabsGotenbergExtension();

        $extension->registerBuilder(ConvertPdfBuilder::class);
        $extension->registerBuilder(EncryptPdfBuilder::class);
        $extension->registerBuilder(EmbedPdfBuilder::class);
        $extension->registerBuilder(FlattenPdfBuilder::class);
        $extension->registerBuilder(HtmlPdfBuilder::class);
        $extension->registerBuilder(LibreOfficePdfBuilder::class);
        $extension->registerBuilder(MarkdownPdfBuilder::class);
        $extension->registerBuilder(MergePdfBuilder::class);
        $extension->registerBuilder(SplitPdfBuilder::class);
        $extension->registerBuilder(StampPdfBuilder::class);
        $extension->registerBuilder(UrlPdfBuilder::class);
        $extension->registerBuilder(WatermarkPdfBuilder::class);

        $extension->registerBuilder(HtmlScreenshotBuilder::class);
        $extension->registerBuilder(MarkdownScreenshotBuilder::class);
        $extension->registerBuilder(UrlScreenshotBuilder::class);

        return $extension;
    }

    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new GotenbergPass());
    }
}
