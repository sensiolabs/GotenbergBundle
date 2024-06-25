<?php

namespace Sensiolabs\GotenbergBundle\RemoteEvent;

use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\RemoteEvent\Exception\ParseException;
use Symfony\Component\RemoteEvent\PayloadConverterInterface;
use Symfony\Component\RemoteEvent\RemoteEvent;

class SuccessPayloadConverter implements PayloadConverterInterface
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
        $matches = [];

        /* @see https://onlinephp.io/c/c2606 */
        if (!preg_match('#[^;]*;\sfilename="?(?P<fileName>[^"]*)"?#', $headers->get('Content-Disposition', ''), $matches)) {
            $matches['fileName'] = ($headers->get('X-Gotenberg-Operation-Id') ?? throw new ParseException('Missing filename in headers')).'.pdf';
        }
        $fileName = $matches['fileName'];

        return new OperationSuccessEvent($fileName, $content, $headers);
    }
}
