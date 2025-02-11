<?php

namespace Sensiolabs\GotenbergBundle\NodeBuilder;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;

interface NodeBuilderInterface
{
    public function getName(): string;

    public function create(): NodeDefinition;
}
