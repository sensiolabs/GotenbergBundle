<?php

namespace Sensiolabs\GotenbergBundle\Enumeration;

enum NodeType: string
{
    case Scalar = 'scalar';
    case Boolean = 'boolean';
    case Integer = 'integer';
    case Float = 'float';
    case Enum = 'enum';
    case Array = 'array';
    case Variable = 'variable';
}
