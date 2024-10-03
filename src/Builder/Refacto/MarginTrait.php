<?php

namespace Sensiolabs\GotenbergBundle\Builder\Refacto;

use Sensiolabs\GotenbergBundle\Enumeration\Unit;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

trait MarginTrait
{
    abstract protected function setData(string $name, mixed $value): static;

    public function margins(float $top, float $bottom, float $left, float $right, Unit $unit = Unit::Inches): static
    {
        return $this
            ->marginTop($top, $unit)
            ->marginBottom($bottom, $unit)
            ->marginLeft($left, $unit)
            ->marginRight($right, $unit)
        ;
    }

    public function marginTop(float $top, Unit $unit = Unit::Inches): static
    {
        return $this->setData('marginTop', $top.$unit->value);
    }

    public function marginBottom(float $bottom, Unit $unit = Unit::Inches): static
    {
        return $this->setData('marginBottom', $bottom.$unit->value);
    }

    public function marginLeft(float $left, Unit $unit = Unit::Inches): static
    {
        return $this->setData('marginLeft', $left.$unit->value);
    }

    public function marginRight(float $right, Unit $unit = Unit::Inches): static
    {
        return $this->setData('marginRight', $right.$unit->value);
    }

    public function configure(OptionsResolver $optionsResolver): void
    {
        $normalize = fn (Options $options, mixed $value): string => is_numeric($value) ? $value.'in' : $value;

        $optionsResolver
            ->setDefined(['marginTop', 'marginBottom', 'marginLeft', 'marginRight'])
            ->setAllowedValues('paperWidth', ['string', 'int', 'float'])
            ->setNormalizer('paperWidth', $normalize)
            ->setAllowedValues('paperHeight', ['string', 'int', 'float'])
            ->setNormalizer('paperHeight', $normalize)
            ->setAllowedValues('marginLeft', ['string', 'int', 'float'])
            ->setNormalizer('marginLeft', $normalize)
            ->setAllowedValues('marginRight', ['string', 'int', 'float'])
            ->setNormalizer('marginRight', $normalize)
        ;
    }
}
