<?php

namespace Sensiolabs\GotenbergBundle\NodeBuilder;

use Sensiolabs\GotenbergBundle\Enumeration\Unit;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;

final class UnitNodeBuilder extends NodeBuilder implements NodeBuilderInterface
{
    public function create(): NodeDefinition
    {
        $node = (new ArrayNodeBuilder(
            $this->name,
            children: [
                new ScalarNodeBuilder('value'),
                new EnumNodeBuilder('unit', values: Unit::cases()),
            ],
        ))->create();

        $node->beforeNormalization()
            ->ifTrue(static function ($value): bool {
                return is_numeric($value) || \is_string($value);
            })
            ->then(static function ($v) {
                [$value, $unit] = Unit::parse($v);

                return [
                    'value' => $value,
                    'unit' => $unit,
                ];
            })
            ->end()
        ;

        return $node;
    }
}
