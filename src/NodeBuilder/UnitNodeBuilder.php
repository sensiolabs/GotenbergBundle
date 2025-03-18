<?php

namespace Sensiolabs\GotenbergBundle\NodeBuilder;

use Sensiolabs\GotenbergBundle\Enumeration\Unit;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;

final class UnitNodeBuilder extends NodeBuilder implements NodeBuilderInterface
{
    public function create(): NodeDefinition
    {

        return (new ScalarNodeBuilder($this->name))->create();

//        $node = (new ArrayNodeBuilder(
//            $this->name,
//            children: [
//                new ScalarNodeBuilder('value'),
//                new EnumNodeBuilder('unit', values: Unit::cases()),
//            ],
//        ))->create();

//        $node->beforeNormalization()
//            ->ifTrue(static function ($value): bool {
//                return is_numeric($value);
//            })
//            ->then(static function (string|int|float $v) {
//                $parse = Unit::parse($v);
//
//                return [
//                    'value' => $parse[0],
//                    'unit' => $parse[1],
//                ];
//            })
//        ;

        return $node;
    }
}
