<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Chromium;

use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\Attributes\WithConfigurationNode;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\Enumeration\UserAgent;
use Sensiolabs\GotenbergBundle\NodeBuilder\ArrayNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\ScalarNodeBuilder;

/**
 * @package Behavior\\Http\\CustomHeaders
 */
trait CustomHttpHeadersTrait
{
    abstract protected function getBodyBag(): BodyBag;

    /**
     * Override the default User-Agent HTTP header.
     *
     * @param UserAgent::*|string $userAgent
     *
     * @example userAgent(UserAgent::AndroidChrome)
     */
    #[WithConfigurationNode(new ScalarNodeBuilder('user_agent', restrictTo: 'string'))]
    public function userAgent(string $userAgent): static
    {
        $this->getBodyBag()->set('userAgent', $userAgent);

        return $this;
    }

    /**
     * Sets extra HTTP headers that Chromium will send when loading the HTML document. (overrides any previous headers).
     *
     * @param array<string, string> $headers
     *
     * @see https://gotenberg.dev/docs/routes#custom-http-headers-chromium
     *
     * @example extraHttpHeaders(['MyHeader' => 'MyValue'])
     */
    #[WithConfigurationNode(new ArrayNodeBuilder('extra_http_headers', normalizeKeys: false, useAttributeAsKey: 'name', prototype: 'variable'))]
    public function extraHttpHeaders(array $headers): static
    {
        if ([] === $headers) {
            $this->getBodyBag()->unset('extraHttpHeaders');

            return $this;
        }

        $this->getBodyBag()->set('extraHttpHeaders', $headers);

        return $this;
    }

    /**
     * Adds extra HTTP headers that Chromium will send when loading the HTML document.
     *
     * @param array<string, string> $headers
     *
     * @example addExtraHttpHeaders(['MyHeader' => 'MyValue'])
     */
    public function addExtraHttpHeaders(array $headers): static
    {
        if ([] === $headers) {
            return $this;
        }

        $current = $this->getBodyBag()->get('extraHttpHeaders', []);

        $this->getBodyBag()->set('extraHttpHeaders', array_merge($current, $headers));

        return $this;
    }

    #[NormalizeGotenbergPayload]
    private function normalizeCustomHttpHeader(): \Generator
    {
        yield 'extraHttpHeaders' => NormalizerFactory::json();
    }
}
