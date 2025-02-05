<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Chromium;

use Sensiolabs\GotenbergBundle\Builder\Attributes\ExposeSemantic;
use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\LoggerAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\RequestAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\Builder\Util\ValidatorFactory;
use Sensiolabs\GotenbergBundle\Enumeration\NodeType;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Symfony\Component\HttpFoundation\Cookie;

/**
 * @see https://gotenberg.dev/docs/routes#cookies-chromium.
 */
trait CookieTrait
{
    use LoggerAwareTrait;
    use RequestAwareTrait;

    abstract protected function getBodyBag(): BodyBag;

    /**
     * @param list<Cookie|array{name: string, value: string, domain: string, path?: string|null, secure?: bool|null, httpOnly?: bool|null, sameSite?: 'Strict'|'Lax'|null}> $cookies
     */
    #[ExposeSemantic('cookies', NodeType::Array, ['default_value' => [], 'prototype' => 'array', 'children' => [
        ['name' => 'name', 'options' => ['required' => true]],
        ['name' => 'value', 'options' => ['required' => true]],
        ['name' => 'domain', 'options' => ['required' => true]],
        ['name' => 'path'],
        ['name' => 'secure', 'node_type' => NodeType::Boolean],
        ['name' => 'httpOnly', 'node_type' => NodeType::Boolean],
        ['name' => 'sameSite', 'node_type' => NodeType::Enum, 'options' => ['values' => ['Strict', 'Lax', 'None']]],
    ]])]
    public function cookies(array $cookies): static
    {
        if ([] === $cookies) {
            $this->getBodyBag()->unset('cookies');

            return $this;
        }

        $this->addCookies($cookies);

        return $this;
    }

    /**
     * Add cookies to store in the Chromium cookie jar.
     *
     * @see https://gotenberg.dev/docs/routes#cookies-chromium
     *
     * @param list<Cookie|array{name: string, value: string, domain: string, path?: string|null, secure?: bool|null, httpOnly?: bool|null, sameSite?: 'Strict'|'Lax'|null}> $cookies
     */
    public function addCookies(array $cookies): static
    {
        if (!ValidatorFactory::cookies($cookies)) {
            throw new InvalidBuilderConfiguration('Invalid cookies schema.');
        }

        $c = $this->getBodyBag()->get('cookies', []);

        foreach ($cookies as $cookie) {
            if ($cookie instanceof Cookie) {
                $c[$cookie->getName()] = $cookie;

                continue;
            }

            $c[$cookie['name']] = $cookie;
        }

        $this->getBodyBag()->set('cookies', $c);

        return $this;
    }

    /**
     * @param Cookie|array{name: string, value: string, domain: string, path?: string|null, secure?: bool|null, httpOnly?: bool|null, sameSite?: 'Strict'|'Lax'|null} $cookie
     */
    public function setCookie(string $name, Cookie|array $cookie): static
    {
        $current = $this->getBodyBag()->get('cookies', []);
        $current[$name] = $cookie;

        $this->getBodyBag()->set('cookies', $current);

        return $this;
    }

    public function forwardCookie(string $name): static
    {
        $request = $this->getCurrentRequest();

        if (null === $request) {
            $this->getLogger()?->debug('Cookie {sensiolabs_gotenberg.cookie_name} cannot be forwarded because there is no Request.', [
                'sensiolabs_gotenberg.cookie_name' => $name,
            ]);

            return $this;
        }

        if (false === $request->cookies->has($name)) {
            $this->getLogger()?->debug('Cookie {sensiolabs_gotenberg.cookie_name} does not exists.', [
                'sensiolabs_gotenberg.cookie_name' => $name,
            ]);

            return $this;
        }

        return $this->setCookie($name, [
            'name' => $name,
            'value' => (string) $request->cookies->get($name),
            'domain' => $request->getHost(),
        ]);
    }

    #[NormalizeGotenbergPayload]
    private function normalizeCookies(): \Generator
    {
        yield 'cookies' => NormalizerFactory::json(false);
    }
}
