<?php

namespace Sensiolabs\GotenbergBundle\Configurator\Pdf;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Pdf\HtmlPdfBuilder;
use Sensiolabs\GotenbergBundle\Configurator\AbstractBuilderConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

final class HtmlPdfBuilderConfigurator extends AbstractBuilderConfigurator
{
    protected static function getBuilderClass(): string
    {
        return HtmlPdfBuilder::class;
    }

    public function setConfigurations(BuilderInterface $builder): void
    {
        $configuration = $this->configuration;
        if (isset($configuration['paper_standard_size']) && (isset($configuration['paper_height']) || isset($configuration['paper_width']))) {
            throw new InvalidConfigurationException('You cannot use "paper_standard_size" when "paper_height", "paper_width" or both are set".');
        }

        parent::setConfigurations($builder);
    }
}
