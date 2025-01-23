<?php

namespace Sensiolabs\GotenbergBundle\Builder\Screenshot;

use Sensiolabs\GotenbergBundle\Builder\AbstractBuilder;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\ChromiumTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\WebhookTrait;

class HtmlScreenshotBuilder extends AbstractBuilder
{
    use ChromiumTrait;
    use WebhookTrait;

    protected function getEndpoint(): string
    {
        return '/forms/chromium/screenshot/html';
    }
}
