<?php

namespace Sensiolabs\GotenbergBundle\PayloadResolver\Screenshot;

use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\HeadersBag;
use Sensiolabs\GotenbergBundle\Enumeration\Part;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\PayloadResolver\AbstractPayloadResolver;
use Sensiolabs\GotenbergBundle\PayloadResolver\Behaviors\ChromiumOptionsTrait;

final class HtmlScreenshotPayloadResolver extends AbstractPayloadResolver
{
    use ChromiumOptionsTrait;

    public function resolveBody(BodyBag $bodyBag): array
    {
        $resolvedData = $this->getBodyOptionsResolver()->resolve($bodyBag->all());

        if (!\array_key_exists(Part::Body->value, $resolvedData) && [] === ($resolvedData['downloadFrom'] ?? [])) {
            throw new MissingRequiredFieldException('Content is required');
        }

        return $resolvedData;
    }

    public function resolveHeaders(HeadersBag $headersBag): array
    {
        return $this->getHeadersOptionsResolver()->resolve($headersBag->all());
    }
}
