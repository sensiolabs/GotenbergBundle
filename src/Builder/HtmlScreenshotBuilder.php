<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Builder\Behaviors\ChromiumTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\WebhookTrait;
use Sensiolabs\GotenbergBundle\Client\Payload;
use Sensiolabs\GotenbergBundle\Enumeration\Part;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Exception\PdfPartRenderingException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HtmlScreenshotBuilder extends AbstractBuilder
{
    use ChromiumTrait { ChromiumTrait::configure as configureChromium; }
    use WebhookTrait { WebhookTrait::configure as configureWebhook; }

    protected function getEndpoint(): string
    {
        return '/forms/chromium/screenshot/html';
    }

    protected function configure(OptionsResolver $bodyOptionsResolver, OptionsResolver $headersOptionsResolver): void
    {
        parent::configure($bodyOptionsResolver, $headersOptionsResolver);
        $this->configureChromium($bodyOptionsResolver, $headersOptionsResolver);
        $this->configureWebhook($bodyOptionsResolver, $headersOptionsResolver);
    }

    protected function validatePayload(Payload $payload): void
    {
        $body = $payload->getPayloadBody();

        if (!\array_key_exists(Part::Body->value, $body) && [] === ($body['downloadFrom'] ?? [])) {
            throw new MissingRequiredFieldException('Content is required');
        }
    }
}
