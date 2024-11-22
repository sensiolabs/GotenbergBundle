<?php

namespace Sensiolabs\GotenbergBundle\Webhook;

use Sensiolabs\GotenbergBundle\RemoteEvent\ErrorGotenbergEvent;
use Sensiolabs\GotenbergBundle\RemoteEvent\SuccessGotenbergEvent;
use Sensiolabs\GotenbergBundle\Utils\HeaderUtils;
use Symfony\Component\HttpFoundation\ChainRequestMatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcher\MethodRequestMatcher;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\RemoteEvent\RemoteEvent;
use Symfony\Component\Webhook\Client\AbstractRequestParser;
use Symfony\Component\Webhook\Exception\RejectWebhookException;

/**
 * @see https://gotenberg.dev/docs/webhook
 */
class GotenbergRequestParser extends AbstractRequestParser
{
    public function __construct(
        private readonly string $idHeaderName = 'Gotenberg-Trace',
        private readonly string $userAgent = 'Gotenberg',
    ) {
    }

    protected function getRequestMatcher(): RequestMatcherInterface
    {
        return new ChainRequestMatcher([
            new MethodRequestMatcher('POST'),
        ]);
    }

    protected function doParse(Request $request, #[\SensitiveParameter] string $secret): RemoteEvent|null
    {
        if (!($id = $request->headers->get($this->idHeaderName))) {
            throw new RejectWebhookException(406, \sprintf('Missing "%s" HTTP request header.', $this->idHeaderName));
        }

        if ($this->userAgent !== ($userAgent = $request->headers->get('User-Agent', ''))) {
            throw new RejectWebhookException(406, \sprintf('Invalid user agent "%s".', $userAgent));
        }

        if ('json' === $request->getContentTypeFormat()) {
            /** @var array{status: int, message: string} $payload */
            $payload = $request->toArray();

            return new ErrorGotenbergEvent(
                $id,
                $payload,
                $payload['status'],
                $payload['message'],
            );
        }

        return new SuccessGotenbergEvent(
            $id,
            $request->getContent(true),
            HeaderUtils::extractFilename($request->headers) ?? '',
            $request->getContentTypeFormat() ?? '',
            HeaderUtils::extractContentLength($request->headers) ?? 0,
        );
    }
}
