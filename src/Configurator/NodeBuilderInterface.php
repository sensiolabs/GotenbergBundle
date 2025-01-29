<?php

namespace Sensiolabs\GotenbergBundle\Configurator;

use Sensiolabs\GotenbergBundle\Builder\Attributes\ExposeSemantic;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;

interface NodeBuilderInterface
{
    public static function create(ExposeSemantic $exposeSemantic): NodeDefinition;
}
