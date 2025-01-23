<?php

namespace Sensiolabs\GotenbergBundle\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\AbstractBuilder;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\ChromiumTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\WebhookTrait;
use Sensiolabs\GotenbergBundle\Builder\BuilderAssetInterface;

class HtmlPdfBuilder extends AbstractBuilder implements BuilderAssetInterface
{
    use ChromiumTrait;
    use WebhookTrait;

    protected function getEndpoint(): string
    {
        return '/forms/chromium/convert/html';
    }
}
