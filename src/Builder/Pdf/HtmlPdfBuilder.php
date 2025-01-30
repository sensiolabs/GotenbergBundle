<?php

namespace Sensiolabs\GotenbergBundle\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\AbstractBuilder;
use Sensiolabs\GotenbergBundle\Builder\Attributes\SemanticNode;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\ChromiumTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\WebhookTrait;
use Sensiolabs\GotenbergBundle\Builder\BuilderAssetInterface;
use Sensiolabs\GotenbergBundle\Enumeration\Part;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;

#[SemanticNode('html')]
class HtmlPdfBuilder extends AbstractBuilder implements BuilderAssetInterface
{
    use ChromiumTrait;
//    use WebhookTrait;

    protected function getEndpoint(): string
    {
        return '/forms/chromium/convert/html';
    }

    protected function validatePayloadBody(): void
    {
        if ($this->getBodyBag()->get(Part::Body->value) === null && $this->getBodyBag()->get('downloadFrom') === null) {
            throw new MissingRequiredFieldException('Content is required');
        }
    }
}
