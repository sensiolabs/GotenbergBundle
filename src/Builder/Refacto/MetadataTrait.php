<?php

namespace Sensiolabs\GotenbergBundle\Builder\Refacto;

use Sensiolabs\GotenbergBundle\Enumeration\Unit;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

trait MetadataTrait
{
    abstract protected function setData(string $name, mixed $value): static;

    public function metadata(array $metadata): static
    {
        return $this->setData('metadata', $metadata);
    }

    public function configure(OptionsResolver $optionsResolver): void
    {
        $optionsResolver
            ->setNormalizer('metadata', fn (Options $options, $value) => ['metadata' => json_encode($value, \JSON_THROW_ON_ERROR)])
        ;
    }
}
