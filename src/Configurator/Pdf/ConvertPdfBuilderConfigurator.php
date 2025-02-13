<?php

namespace Sensiolabs\GotenbergBundle\Configurator\Pdf;

use Sensiolabs\GotenbergBundle\Builder\Pdf\ConvertPdfBuilder;
use Sensiolabs\GotenbergBundle\Configurator\AbstractBuilderConfigurator;

final class ConvertPdfBuilderConfigurator extends AbstractBuilderConfigurator
{
    protected static function getBuilderClass(): string
    {
        return ConvertPdfBuilder::class;
    }
}
