<?php

namespace Sensiolabs\GotenbergBundle\Configurator\Pdf;

use Sensiolabs\GotenbergBundle\Builder\Attributes\ExposeSemantic;
use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Pdf\HtmlPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\MergePdfBuilder;
use Sensiolabs\GotenbergBundle\Configurator\AbstractBuilderConfigurator;

final class MergePdfBuilderConfigurator extends AbstractBuilderConfigurator
{
    protected static function getBuilderClass(): string
    {
        return MergePdfBuilder::class;
    }
}
