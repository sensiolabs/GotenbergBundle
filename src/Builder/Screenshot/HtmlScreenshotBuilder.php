<?php

namespace Sensiolabs\GotenbergBundle\Builder\Screenshot;

use Sensiolabs\GotenbergBundle\Builder\AbstractBuilder;
use Sensiolabs\GotenbergBundle\Builder\Attributes\SemanticNode;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\ChromiumScreenshotTrait;
use Sensiolabs\GotenbergBundle\Enumeration\Part;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;

/**
 * @see https://gotenberg.dev/docs/routes#screenshots-route
 */
#[SemanticNode('html')]
final class HtmlScreenshotBuilder extends AbstractBuilder
{
    use ChromiumScreenshotTrait;

    protected function getEndpoint(): string
    {
        return '/forms/chromium/screenshot/html';
    }

    protected function validatePayloadBody(): void
    {
        if ($this->getBodyBag()->get(Part::Body->value) === null && $this->getBodyBag()->get('downloadFrom') === null) {
            throw new MissingRequiredFieldException('Content is required');
        }
    }
}
