<?php

namespace Sensiolabs\GotenbergBundle\Configurator\Pdf;

use Sensiolabs\GotenbergBundle\Builder\Pdf\UrlPdfBuilder;
use Sensiolabs\GotenbergBundle\Configurator\AbstractBuilderConfigurator;

final class UrlPdfBuilderConfigurator extends AbstractBuilderConfigurator
{
    protected static function getBuilderClass(): string
    {
        return UrlPdfBuilder::class;
    }
}
