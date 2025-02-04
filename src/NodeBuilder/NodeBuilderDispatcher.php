<?php

namespace Sensiolabs\GotenbergBundle\NodeBuilder;

use Sensiolabs\GotenbergBundle\Builder\Attributes\ExposeSemantic;
use Sensiolabs\GotenbergBundle\Enumeration\NodeType;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;

final class NodeBuilderDispatcher
{
    public static function getNode(ExposeSemantic $exposeSemantic): NodeDefinition
    {
        return match ($exposeSemantic->nodeType) {
            NodeType::Scalar => ScalarNodeBuilder::create($exposeSemantic),
            NodeType::Boolean => BooleanNodeBuilder::create($exposeSemantic),
            NodeType::Integer => IntegerNodeBuilder::create($exposeSemantic),
            NodeType::Float => FloatNodeBuilder::create($exposeSemantic),
            NodeType::Enum => EnumNodeBuilder::create($exposeSemantic),
            NodeType::Array => ArrayNodeBuilder::create($exposeSemantic),
            NodeType::Variable => VariableNodeBuilder::create($exposeSemantic),
        };
    }
}
