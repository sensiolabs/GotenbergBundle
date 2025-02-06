<?php

namespace Sensiolabs\GotenbergBundle\Configurator\Pdf;

use Sensiolabs\GotenbergBundle\Builder\Pdf\LibreOfficePdfBuilder;
use Sensiolabs\GotenbergBundle\Configurator\AbstractBuilderConfigurator;

final class LibreOfficePdfBuilderConfigurator extends AbstractBuilderConfigurator
{
    protected static function getBuilderClass(): string
    {
        return LibreOfficePdfBuilder::class;
    }
}
