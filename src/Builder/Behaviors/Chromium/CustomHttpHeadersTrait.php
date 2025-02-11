<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Chromium;

use Sensiolabs\GotenbergBundle\Builder\Attributes\ExposeSemantic;
use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\Enumeration\UserAgent;
use Sensiolabs\GotenbergBundle\NodeBuilder\ArrayNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\ScalarNodeBuilder;

trait CustomHttpHeadersTrait
{
    abstract protected function getBodyBag(): BodyBag;

    /**
     * Override the default User-Agent HTTP header. (default None).
     *
     * @param UserAgent::*|string $userAgent
     *
     * @see https://gotenberg.dev/docs/routes#custom-http-headers-chromium
     */
    #[ExposeSemantic(new ScalarNodeBuilder('user_agent', restrictTo: 'string'))]
    public function userAgent(string $userAgent): static
    {
        $this->getBodyBag()->set('userAgent', $userAgent);

        return $this;
    }

    /**
     * Sets extra HTTP headers that Chromium will send when loading the HTML
     * document. (default None). (overrides any previous headers).
     *
     * @see https://gotenberg.dev/docs/routes#custom-http-headers-chromium
     *
     * @param array<string, string> $headers
     */
    #[ExposeSemantic(new ArrayNodeBuilder('extra_http_headers', normalizeKeys: false, useAttributeAsKey: 'name', prototype: 'variable'))]
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
     * Adds extra HTTP headers that Chromium will send when loading the HTML
     * document. (default None).
     *
     * @see https://gotenberg.dev/docs/routes#custom-http-headers-chromium
     *
     * @param array<string, string> $headers
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
