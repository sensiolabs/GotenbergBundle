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

    public function testSetCookieArray(): void
    {
        $this->getDefaultBuilder()
            ->setCookie('my_cookie', [
                'name' => 'my_cookie',
                'value' => 'symfony',
                'domain' => 'symfony.com',
                'secure' => true,
                'httpOnly' => true,
                'sameSite' => 'Lax',
            ])
            ->generate()
        ;

        $this->assertGotenbergFormData('cookies', '[{"name":"my_cookie","value":"symfony","domain":"symfony.com","secure":true,"httpOnly":true,"sameSite":"Lax"}]');
    }
}
