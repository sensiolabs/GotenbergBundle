<?php

namespace Sensiolabs\GotenbergBundle\Webhook;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Sensiolabs\GotenbergBundle\RemoteEvent\SuccessPayloadConverter;
use Symfony\Component\HttpFoundation\ChainRequestMatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcher\HeaderRequestMatcher;
use Symfony\Component\HttpFoundation\RequestMatcher\MethodRequestMatcher;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\RemoteEvent\RemoteEvent;
use Symfony\Component\Webhook\Client\AbstractRequestParser;
use Symfony\Component\Webhook\Exception\RejectWebhookException;

class SuccessWebhookRequestParser extends AbstractRequestParser implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function __construct(
        private readonly SuccessPayloadConverter $payloadConverter,
    ) {
        $this->logger = new NullLogger();
    }

    protected function getRequestMatcher(): RequestMatcherInterface
    {
        $requestMatchers = [
            new MethodRequestMatcher('POST'),
        ];
        // Class introduced in Symfony 7.1
        if (class_exists(HeaderRequestMatcher::class)) {
            $requestMatchers[] = new HeaderRequestMatcher('Gotenberg-Trace');
            $requestMatchers[] = new HeaderRequestMatcher('X-Gotenberg-Operation-Id');
            $requestMatchers[] = new HeaderRequestMatcher('Content-Disposition');
        }

        return new ChainRequestMatcher($requestMatchers);
    }

    protected function doParse(Request $request, #[\SensitiveParameter] string $secret): RemoteEvent|null
    {
        if (!class_exists(HeaderRequestMatcher::class)) {
            $this->checkForHeaders($request, ['Gotenberg-Trace', 'X-Gotenberg-Operation-Id', 'Content-Disposition']);
        }

        $this->logger?->debug('Success request matched', ['headers' => $request->headers->all(), 'content' => $request->getContent()]);

        return $this->payloadConverter->convert(['headers' => $request->headers, 'content' => $request->getContent()]);
    }

    /**
     * @param list<string> $headers
     */
    private function checkForHeaders(Request $request, array $headers): void
    {
        foreach ($headers as $header) {
            if (!$request->headers->has($header)) {
                throw new RejectWebhookException(406, sprintf('Missing "%s" header.', $header));
            }
        }
    }
}
