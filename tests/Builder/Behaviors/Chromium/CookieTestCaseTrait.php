<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\Chromium;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\BehaviorTrait;
use Symfony\Component\HttpFoundation\Cookie;

/**
 * @template T of BuilderInterface
 */
trait CookieTestCaseTrait
{
    /** @use BehaviorTrait<T> */
    use BehaviorTrait;

    abstract protected function assertGotenbergFormData(string $field, string $expectedValue): void;

    public function testCookies(): void
    {
        $this->getDefaultBuilder()
            ->cookies([[
                'name' => 'my_cookie',
                'value' => 'symfony',
                'domain' => 'symfony.com',
                'secure' => true,
                'httpOnly' => true,
                'sameSite' => 'Lax',
            ]])
            ->generate()
        ;

        $this->assertGotenbergFormData('cookies', '[{"name":"my_cookie","value":"symfony","domain":"symfony.com","secure":true,"httpOnly":true,"sameSite":"Lax"}]');
    }

    public function testAddCookies(): void
    {
        $this->getDefaultBuilder()
            ->cookies([[
                'name' => 'my_cookie',
                'value' => 'symfony',
                'domain' => 'symfony.com',
                'secure' => true,
                'httpOnly' => true,
                'sameSite' => 'Lax',
            ]])
            ->addCookies([[
                'name' => 'cook',
                'value' => 'sensiolabs',
                'domain' => 'sensiolabs.com',
                'secure' => true,
                'httpOnly' => true,
                'sameSite' => 'Lax',
            ]])
            ->generate()
        ;

        $this->assertGotenbergFormData('cookies', '[{"name":"my_cookie","value":"symfony","domain":"symfony.com","secure":true,"httpOnly":true,"sameSite":"Lax"},{"name":"cook","value":"sensiolabs","domain":"sensiolabs.com","secure":true,"httpOnly":true,"sameSite":"Lax"}]');
    }

    public function testSetCookieObject(): void
    {
        $this->getDefaultBuilder()
            ->setCookie('my_cookie', new Cookie('my_cookie', 'value', domain: 'symfony.com'))
            ->generate()
        ;

        $this->assertGotenbergFormData('cookies', '[{"name":"my_cookie","value":"value","domain":"symfony.com","path":"\/","secure":false,"httpOnly":true,"sameSite":"lax"}]');
    }

    //    public function setCookieArray(string $name, Cookie|array $cookie): static
    //    {
    //        $current = $this->getBodyBag()->get('cookies', []);
    //        $current[$name] = $cookie;
    //
    //        $this->getBodyBag()->set('cookies', $current);
    //
    //        return $this;
    //    }
    //
    //    public function forwardCookie(string $name): static
    //    {
    //        $request = $this->getCurrentRequest();
    //
    //        if (null === $request) {
    //            $this->getLogger()?->debug('Cookie {sensiolabs_gotenberg.cookie_name} cannot be forwarded because there is no Request.', [
    //                'sensiolabs_gotenberg.cookie_name' => $name,
    //            ]);
    //
    //            return $this;
    //        }
    //
    //        if (false === $request->cookies->has($name)) {
    //            $this->getLogger()?->debug('Cookie {sensiolabs_gotenberg.cookie_name} does not exists.', [
    //                'sensiolabs_gotenberg.cookie_name' => $name,
    //            ]);
    //
    //            return $this;
    //        }
    //
    //        return $this->setCookie($name, [
    //            'name' => $name,
    //            'value' => (string) $request->cookies->get($name),
    //            'domain' => $request->getHost(),
    //        ]);
    //    }
}
