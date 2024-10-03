<?php

namespace Sensiolabs\GotenbergBundle\Builder\Refacto\V8;

use Sensiolabs\GotenbergBundle\Builder\Refacto\AbstractBuilder;
use Sensiolabs\GotenbergBundle\Builder\Refacto\MarginTrait;
use Sensiolabs\GotenbergBundle\Builder\Refacto\MetadataTrait;
use Sensiolabs\GotenbergBundle\Builder\Refacto\PaperSizeTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HtmlPdf extends AbstractBuilder
{
    use MarginTrait, MetadataTrait, PaperSizeTrait {
        MarginTrait::configure as protected configureMargin;
        MetadataTrait::configure as protected configureMetadata;
        PaperSizeTrait::configure as protected configurePaperSize;
    }

    protected const ENDPOINT = '/forms/chromium/convert/html';

    protected function getEndpoint(): string
    {
        return self::ENDPOINT;
    }

    protected function configure(OptionsResolver $optionsResolver): void
    {
        $this->configureMargin($optionsResolver);
        $this->configureMetadata($optionsResolver);
        $this->configurePaperSize($optionsResolver);
    }
}
