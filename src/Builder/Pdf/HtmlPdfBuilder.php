<?php

namespace Sensiolabs\GotenbergBundle\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\AbstractBuilder;
use Sensiolabs\GotenbergBundle\Builder\Attributes\ExposeSemantic;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\ChromiumTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\WebhookTrait;
use Sensiolabs\GotenbergBundle\Builder\BuilderAssetInterface;
use Sensiolabs\GotenbergBundle\Configurator\NodeBuilderDispatcher;
use Sensiolabs\GotenbergBundle\Enumeration\Part;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class HtmlPdfBuilder extends AbstractBuilder implements BuilderAssetInterface
{
    use ChromiumTrait { ChromiumTrait::normalize as chromiumNormalize; }
    use WebhookTrait;

    protected function normalize(): \Generator
    {
        yield from $this->chromiumNormalize();
    }

    protected function validatePayload(): void
    {
        if ($this->getBodyBag()->get(Part::Body->value) === null && $this->getBodyBag()->get('downloadFrom') === null) {
            throw new MissingRequiredFieldException('Content is required');
        }
    }

    protected function getEndpoint(): string
    {
        return '/forms/chromium/convert/html';
    }
}
