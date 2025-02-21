<?php

namespace Sensiolabs\GotenbergBundle;

use Sensiolabs\GotenbergBundle\Builder\Pdf\ConvertPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\HtmlPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\LibreOfficePdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\MarkdownPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\MergePdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\SplitPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\UrlPdfBuilder;
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

    public function build(ContainerBuilder $container): void
    {
        /** @var SensiolabsGotenbergExtension $extension */
        $extension = $container->getExtension('sensiolabs_gotenberg');

        $extension->registerBuilder('pdf', ConvertPdfBuilder::class);
        $extension->registerBuilder('pdf', HtmlPdfBuilder::class);
        $extension->registerBuilder('pdf', LibreOfficePdfBuilder::class);
        $extension->registerBuilder('pdf', MarkdownPdfBuilder::class);
        $extension->registerBuilder('pdf', MergePdfBuilder::class);
        $extension->registerBuilder('pdf', SplitPdfBuilder::class);
        $extension->registerBuilder('pdf', UrlPdfBuilder::class);

        $extension->registerBuilder('screenshot', HtmlScreenshotBuilder::class);
        $extension->registerBuilder('screenshot', MarkdownScreenshotBuilder::class);
        $extension->registerBuilder('screenshot', UrlScreenshotBuilder::class);

        $container->addCompilerPass(new GotenbergPass());
    }
}
