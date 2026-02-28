<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Chromium;

use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\Attributes\WithConfigurationNode;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\LoggerAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\NodeBuilder\ArrayNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\BooleanNodeBuilder;

/**
 * @package Behavior\\FailOn
 */
trait FailOnTrait
{
    use LoggerAwareTrait;

    abstract protected function getBodyBag(): BodyBag;

    /**
     * Return a 409 Conflict response if the HTTP status code from
     * the main page is not acceptable. (default [499,599]). (overrides any previous configuration).
     *
     * @see https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#invalid-http-status-codes
     *
     * @param array<int, int> $statusCodes
     *
     * @example failOnHttpStatusCodes([401, 403])
     */
    #[WithConfigurationNode(new ArrayNodeBuilder('fail_on_http_status_codes', prototype: 'integer'))]
    public function failOnHttpStatusCodes(array $statusCodes): static
    {
        $this->getBodyBag()->set('failOnHttpStatusCodes', $statusCodes);

        return $this;
    }

    /**
     * Return a 409 Conflict response if the HTTP status code from at least one resource is not acceptable. (overrides any previous configuration).
     *
     * @see https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#invalid-http-status-codes
     *
     * @param list<int<100, 599>> $statusCodes
     *
     * @example failOnResourceHttpStatusCodes([401, 403])
     */
    #[WithConfigurationNode(new ArrayNodeBuilder('fail_on_resource_http_status_codes', prototype: 'integer'))]
    public function failOnResourceHttpStatusCodes(array $statusCodes): static
    {
        $this->logWarningIfVersionIs('<', '8.13', 'The option failOnResourceHttpStatusCodes is not available.');

        $this->getBodyBag()->set('failOnResourceHttpStatusCodes', $statusCodes);

        return $this;
    }

    /**
     * Forces GotenbergPdf to return a 409 Conflict response if Chromium fails to load at least one resource.
     * (default false).
     *
     * @see https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#http--networking
     *
     * @example failOnResourceLoadingFailed() // is same as `->failOnResourceLoadingFailed(true)`
     */
    #[WithConfigurationNode(new BooleanNodeBuilder('fail_on_resource_loading_failed'))]
    public function failOnResourceLoadingFailed(bool $bool = true): static
    {
        $this->logWarningIfVersionIs('<', '8.13', 'The option failOnResourceLoadingFailed is not available.');

        $this->getBodyBag()->set('failOnResourceLoadingFailed', $bool);

        return $this;
    }

    /**
     * Forces GotenbergPdf to return a 409 Conflict response if there are
     * exceptions in the Chromium console. (default false).
     *
     * @see https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#console
     *
     * @example failOnConsoleExceptions() // is same as `->failOnConsoleExceptions(true)`
     */
    #[WithConfigurationNode(new BooleanNodeBuilder('fail_on_console_exceptions'))]
    public function failOnConsoleExceptions(bool $bool = true): static
    {
        $this->getBodyBag()->set('failOnConsoleExceptions', $bool);

        return $this;
    }

    /**
     * Exclude resources from failOnResourceHttpStatusCodes checks based on their hostname.
     *
     * The ignoreResourceHttpStatusDomains option allows you to exclude specific domains from the resource HTTP status
     * code checks. A match happens if the hostname equals the domain or is a subdomain of it
     * (e.g., browser.sentry-cdn.com matches sentry-cdn.com).
     *
     * Values are normalized (trimmed, lowercased) and may be provided as:
     *
     * example.com
     * .example.com or .example.com
     * example.com:443 (port is ignored)
     * https://example.com/path (scheme/path are ignored)
     *
     * @see https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#invalid-http-status-codes
     *
     * @param list<string> $domains
     *
     * @example ignoreResourceHttpStatusDomains(['sentry-cdn.com', 'analytics.example.com'])
     */
    #[WithConfigurationNode(new ArrayNodeBuilder('ignore_resource_http_status_domains', prototype: 'scalar'))]
    public function ignoreResourceHttpStatusDomains(array $domains): static
    {
        $this->logWarningIfVersionIs('<', '8.26', 'The option ignoreResourceHttpStatusDomains is not available.');

        $this->getBodyBag()->set('ignoreResourceHttpStatusDomains', $domains);

        return $this;
    }

    #[NormalizeGotenbergPayload]
    private function normalizeFailOn(): \Generator
    {
        yield 'failOnHttpStatusCodes' => NormalizerFactory::json(false);
        yield 'failOnResourceHttpStatusCodes' => NormalizerFactory::json(false);
        yield 'failOnResourceLoadingFailed' => NormalizerFactory::bool();
        yield 'failOnConsoleExceptions' => NormalizerFactory::bool();
        yield 'ignoreResourceHttpStatusDomains' => NormalizerFactory::json(false);
    }
}
