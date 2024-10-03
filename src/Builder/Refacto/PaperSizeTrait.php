<?php

namespace Sensiolabs\GotenbergBundle\Builder\Refacto;

use Sensiolabs\GotenbergBundle\Enumeration\PaperSizeInterface;
use Sensiolabs\GotenbergBundle\Enumeration\Unit;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

trait PaperSizeTrait
{
    abstract protected function setData(string $name, mixed $value): static;

    public function paperWidth(float $width, Unit $unit = Unit::Inches): static
    {
        return $this->setData('paperWidth', $width.$unit->value);
    }

    public function paperHeight(float $height, Unit $unit = Unit::Inches): static
    {
        return $this->setData('paperHeight', $height.$unit->value);
    }

    public function paperStandardSize(PaperSizeInterface $paperSize): static
    {
        return $this
            ->paperWidth($paperSize->width(), $paperSize->unit())
            ->paperHeight($paperSize->height(), $paperSize->unit())
        ;
    }

    public function configure(OptionsResolver $optionsResolver): void
    {
        $normalize = fn (Options $options, mixed $value): string => is_numeric($value) ? $value.'in' : $value;

        $optionsResolver
            ->setDefined(['paperWidth', 'paperHeight'])
            ->setAllowedValues('paperWidth', ['string', 'int', 'float'])
            ->setNormalizer('paperWidth', $normalize)
            ->setAllowedValues('paperHeight', ['string', 'int', 'float'])
            ->setNormalizer('paperHeight', $normalize)
        ;
    }
}
