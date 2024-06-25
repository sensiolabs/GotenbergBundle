<?php

namespace Sensiolabs\GotenbergBundle\Webhook;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Sensiolabs\GotenbergBundle\RemoteEvent\ErrorPayloadConverter;
use Symfony\Component\HttpFoundation\ChainRequestMatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcher\HeaderRequestMatcher;
use Symfony\Component\HttpFoundation\RequestMatcher\IsJsonRequestMatcher;
use Symfony\Component\HttpFoundation\RequestMatcher\MethodRequestMatcher;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\RemoteEvent\RemoteEvent;
use Symfony\Component\Webhook\Client\AbstractRequestParser;
use Symfony\Component\Webhook\Exception\RejectWebhookException;

class ErrorWebhookRequestParser extends AbstractRequestParser implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function __construct(
        private readonly ErrorPayloadConverter $payloadConverter,
    ) {
        $this->logger = new NullLogger();
    }

    protected function getRequestMatcher(): RequestMatcherInterface
    {
        $requestMatchers = [
            new MethodRequestMatcher('POST'),
            new IsJsonRequestMatcher(),
        ];
        // Class introduced in Symfony 7.1
        if (class_exists(HeaderRequestMatcher::class)) {
            $requestMatchers[] = new HeaderRequestMatcher('Gotenberg-Trace');
            $requestMatchers[] = new HeaderRequestMatcher('X-Gotenberg-Operation-Id');
        }

        return new ChainRequestMatcher($requestMatchers);
    }

    protected function doParse(Request $request, #[\SensitiveParameter] string $secret): RemoteEvent|null
    {
        if (!$request->headers->has('Gotenberg-Trace')) {
            throw new RejectWebhookException(406, 'Missing "Gotenberg-Trace" header');
        }
        if (!$request->headers->has('X-Gotenberg-Operation-Id')) {
            throw new RejectWebhookException(406, 'Missing "X-Gotenberg-Operation-Id" header');
        }

        $this->logger?->debug('Error request matched', ['headers' => $request->headers->all(), 'content' => $request->getContent()]);

        return $this->payloadConverter->convert(['headers' => $request->headers, 'content' => $request->getContent()]);
    }
}
