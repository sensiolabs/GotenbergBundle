<?php

namespace Sensiolabs\GotenbergBundle\RemoteEvent;

use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\RemoteEvent\Exception\ParseException;
use Symfony\Component\RemoteEvent\PayloadConverterInterface;
use Symfony\Component\RemoteEvent\RemoteEvent;

class ErrorPayloadConverter implements PayloadConverterInterface
{
    /**
     * @param array{headers?: HeaderBag, content?: string} $payload
     */
    public function convert(array $payload): RemoteEvent
    {
        if (!isset($payload['headers']) || !isset($payload['content'])) {
            throw new ParseException('Invalid payload: missing "headers" or "content".');
        }
        $headers = $payload['headers'];
        $content = $payload['content'];

        return new OperationErrorEvent($content, $headers);
    }
}
