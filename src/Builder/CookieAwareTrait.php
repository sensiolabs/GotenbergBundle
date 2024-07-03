<?php

declare(strict_types=1);

namespace Sensiolabs\GotenbergBundle\Builder;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mime\Part\DataPart;

trait CookieAwareTrait
{
    /**
     * Cookies to store in the Chromium cookie jar. (overrides any previous cookies).
     *
     * @see https://gotenberg.dev/docs/routes#cookies-chromium
     *
     * @param list<Cookie|array{name: string, value: string, domain: string, path?: string|null, secure?: bool|null, httpOnly?: bool|null, sameSite?: 'Strict'|'Lax'|null}> $cookies
     */
    abstract public function cookies(array $cookies): static;

    /**
     * @param array{cookies?: array<string, Cookie|array{name: string, value: string, domain: string, path?: string|null, secure?: bool|null, httpOnly?: bool|null, sameSite?: 'Strict'|'Lax'|null}>} $formFields
     * @param list<Cookie|array{name: string, value: string, domain: string, path?: string|null, secure?: bool|null, httpOnly?: bool|null, sameSite?: 'Strict'|'Lax'|null}>                           $cookies
     */
    private function withCookies(array &$formFields, array $cookies): static
    {
        if ([] === $cookies) {
            unset($formFields['cookies']);

            return $this;
        }

        $formFields['cookies'] = [];

        foreach ($cookies as $cookie) {
            if ($cookie instanceof Cookie) {
                $this->setCookie($cookie->getName(), $cookie);

                continue;
            }

            $this->setCookie($cookie['name'], $cookie);
        }

        return $this;
    }

    /**
     * @param Cookie|array{name: string, value: string, domain: string, path?: string|null, secure?: bool|null, httpOnly?: bool|null, sameSite?: 'Strict'|'Lax'|null} $cookie
     */
    abstract public function setCookie(string $key, Cookie|array $cookie): static;

    /**
     * @param array{cookies?: array<string, Cookie|array{name: string, value: string, domain: string, path?: string|null, secure?: bool|null, httpOnly?: bool|null, sameSite?: 'Strict'|'Lax'|null}>} $formFields
     * @param Cookie|array{name: string, value: string, domain: string, path?: string|null, secure?: bool|null, httpOnly?: bool|null, sameSite?: 'Strict'|'Lax'|null}                                 $cookie
     */
    private function withCookie(array &$formFields, string $key, Cookie|array $cookie): static
    {
        $formFields['cookies'] ??= [];
        $formFields['cookies'][$key] = $cookie;

        return $this;
    }

    /**
     *  Add cookies to store in the Chromium cookie jar.
     *
     * @see https://gotenberg.dev/docs/routes#cookies-chromium
     *
     * @param list<Cookie|array{name: string, value: string, domain: string, path?: string|null, secure?: bool|null, httpOnly?: bool|null, sameSite?: 'Strict'|'Lax'|null}> $cookies
     */
    public function addCookies(array $cookies): static
    {
        foreach ($cookies as $cookie) {
            if ($cookie instanceof Cookie) {
                $this->setCookie($cookie->getName(), $cookie);

                continue;
            }

            $this->setCookie($cookie['name'], $cookie);
        }

        return $this;
    }

    private function forwardCookieFromRequest(Request|null $request, string $key, LoggerInterface|null $logger = null): static
    {
        if (null === $request) {
            $logger?->debug('Cookie {sensiolabs_gotenberg.cookie_name} cannot be forwarded because there is no Request.', [
                'sensiolabs_gotenberg.cookie_name' => $key,
            ]);

            return $this;
        }

        if (false === $request->cookies->has($key)) {
            $logger?->debug('Cookie {sensiolabs_gotenberg.cookie_name} does not exists.', [
                'sensiolabs_gotenberg.cookie_name' => $key,
            ]);

            return $this;
        }

        $value = $request->cookies->get($key);
        $domain = $request->getHost();

        return $this->setCookie($key, [
            'name' => $key,
            'value' => $value,
            'domain' => $domain,
        ]);
    }

    abstract public function forwardCookie(string $name): static;

    /**
     * @param (\Closure(string, mixed): array<string, mixed>) $encoder
     *
     * @return array<string, array<string|int, mixed>|string|\Stringable|int|float|bool|\BackedEnum|DataPart>
     */
    private function cookieNormalizer(mixed $value, callable $encoder): array
    {
        $cookies = array_values($value);
        $data = [];

        foreach ($cookies as $cookie) {
            if ($cookie instanceof Cookie) {
                $data[] = [
                    'name' => $cookie->getName(),
                    'value' => $cookie->getValue(),
                    'domain' => $cookie->getDomain(),
                    'path' => $cookie->getPath(),
                    'secure' => $cookie->isSecure(),
                    'httpOnly' => $cookie->isHttpOnly(),
                    'sameSite' => null !== ($sameSite = $cookie->getSameSite()) ? ucfirst(strtolower($sameSite)) : null,
                ];

                continue;
            }

            $data[] = $cookie;
        }

        return $encoder('cookies', $data);
    }
}
