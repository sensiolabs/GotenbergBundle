<?php

namespace Sensiolabs\GotenbergBundle\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\AbstractBuilder;
use Sensiolabs\GotenbergBundle\Builder\Attributes\WithBuilderConfiguration;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\ChromiumPdfTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\EmbedTrait;
use Sensiolabs\GotenbergBundle\Builder\BuilderAssetInterface;
use Sensiolabs\GotenbergBundle\Enumeration\Part;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;

/**
 * You may have the possibility to convert HTML or Twig files into PDF.
 *
 * @see https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf
 */
#[WithBuilderConfiguration(type: 'pdf', name: 'html')]
final class HtmlPdfBuilder extends AbstractBuilder implements BuilderAssetInterface
{
    use ChromiumPdfTrait;
    use EmbedTrait;

    public const ENDPOINT = '/forms/chromium/convert/html';

    protected function getEndpoint(): string
    {
        return self::ENDPOINT;
    }

    protected function validatePayloadBody(): void
    {
        if ($this->getBodyBag()->get(Part::Body->value) === null && $this->getBodyBag()->get('downloadFrom') === null) {
            throw new MissingRequiredFieldException('Content is required');
        }
    }
}
