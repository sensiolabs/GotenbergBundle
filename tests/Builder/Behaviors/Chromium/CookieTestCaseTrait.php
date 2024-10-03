<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\Chromium;

use Psr\Log\LoggerInterface;
use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\BehaviorTrait;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @template T of BuilderInterface
 */
trait CookieTestCaseTrait
{
    /** @use BehaviorTrait<T> */
    use BehaviorTrait;

    abstract protected function assertGotenbergFormData(string $field, string $expectedValue): void;

    public function testSetCookiesWithMultipleArrays(): void
    {
        $this->getDefaultBuilder()
            ->cookies([
                [
                    'name' => 'my_cookie',
                    'value' => 'symfony',
                    'domain' => 'symfony.com',
                    'secure' => true,
                    'httpOnly' => true,
                    'sameSite' => 'Lax',
                ],
                [
                    'name' => 'cook',
                    'value' => 'sensiolabs',
                    'domain' => 'sensiolabs.com',
                    'secure' => true,
                    'httpOnly' => true,
                    'sameSite' => 'Lax',
                ],
            ])
            ->generate()
        ;

        $this->assertGotenbergFormData('cookies', '[{"name":"my_cookie","value":"symfony","domain":"symfony.com","secure":true,"httpOnly":true,"sameSite":"Lax"},{"name":"cook","value":"sensiolabs","domain":"sensiolabs.com","secure":true,"httpOnly":true,"sameSite":"Lax"}]');
    }

    public function testSetAddCookiesWithSimpleArrayToExistingCookies(): void
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

    public function testSetCookieWithCookieObject(): void
    {
        $this->getDefaultBuilder()
            ->setCookie('my_cookie', new Cookie('my_cookie', 'value', domain: 'symfony.com'))
            ->generate()
        ;

        $this->assertGotenbergFormData('cookies', '[{"name":"my_cookie","value":"value","domain":"symfony.com","path":"\/","secure":false,"httpOnly":true,"sameSite":"lax"}]');
    }

    public function testToUnsetExistingCookie(): void
    {
        $builder = $this->getDefaultBuilder()
            ->setCookie('my_cookie', new Cookie('my_cookie', 'value', domain: 'symfony.com'))
        ;

        self::assertArrayHasKey('cookies', $builder->getBodyBag()->all());

        $builder->cookies([]);
        self::assertArrayNotHasKey('cookies', $builder->getBodyBag()->all());
    }

    public function testSetCookieWithSimpleArray(): void
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

    public function testToAddMultipleTimeTheSameCookie(): void
    {
        $cookie = new Cookie('my_cookie', 'value', domain: 'symfony.com');
        $builder = $this->getDefaultBuilder()
            ->setCookie('my_cookie', $cookie)
        ;

        self::assertArrayHasKey('cookies', $builder->getBodyBag()->all());

        $builder->addCookies([$cookie]);
        self::assertArrayHasKey('cookies', $builder->getBodyBag()->all());
        self::assertCount(1, $builder->getBodyBag()->all()['cookies']);
    }

    public function testToForwardCookiesWithNoCurrentRequest(): void
    {
        $this->dependencies->set('request_stack', new RequestStack());

        $builder = $this->getDefaultBuilder()
            ->forwardCookie('my_cookie')
        ;

        self::assertArrayNotHasKey('cookies', $builder->getBodyBag()->all());
    }

    public function testToForwardCookiesWithCurrentRequest(): void
    {
        $request = new Request();
        $request->setMethod('GET');
        $request->cookies->set('my_cookie', new Cookie('my_cookie', 'value', domain: 'symfony.com'));

        $this->dependencies->set('request_stack', new RequestStack([$request]));
        $this->dependencies->set('logger', $this->getMockBuilder(LoggerInterface::class));

        $builder = $this->getDefaultBuilder()
            ->forwardCookie('my_cookie')
        ;

        self::assertArrayHasKey('cookies', $builder->getBodyBag()->all());
    }

    public function testToForwardCookiesWithCurrentRequestWithoutCookies(): void
    {
        $request = new Request();
        $request->setMethod('GET');

        $this->dependencies->set('request_stack', new RequestStack([$request]));
        $this->dependencies->set('logger', $this->getMockBuilder(LoggerInterface::class));

        $builder = $this->getDefaultBuilder()
            ->forwardCookie('my_cookie')
        ;

        self::assertArrayNotHasKey('cookies', $builder->getBodyBag()->all());
    }

    public function testRequestStackDependencyRequirementForForwardCookies(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('RequestStack is required to use "Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\RequestAwareTrait::getCurrentRequest" method. Try to run "composer require symfony/http-foundation".');

        $this->getDefaultBuilder()
            ->forwardCookie('my_cookie')
        ;
    }
}
