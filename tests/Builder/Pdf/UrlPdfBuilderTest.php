<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Pdf;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use Sensiolabs\GotenbergBundle\Builder\GotenbergFileResult;
use Sensiolabs\GotenbergBundle\Builder\Pdf\AbstractChromiumPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\AbstractPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\UrlPdfBuilder;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Processor\NullProcessor;
use Sensiolabs\GotenbergBundle\Tests\Builder\AbstractBuilderTestCase;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

#[CoversClass(UrlPdfBuilder::class)]
#[UsesClass(AbstractChromiumPdfBuilder::class)]
#[UsesClass(AbstractPdfBuilder::class)]
#[UsesClass(GotenbergFileResult::class)]
final class UrlPdfBuilderTest extends AbstractBuilderTestCase
{
    public function testEndpointIsCorrect(): void
    {
        $this->gotenbergClient
            ->expects($this->once())
            ->method('call')
            ->with(
                $this->equalTo('/forms/chromium/convert/url'),
                $this->anything(),
                $this->anything(),
            )
        ;

        $this->getUrlPdfBuilder()
            ->url('https://google.com')
            ->generate()
        ;
    }

    public function testCanProvideUrl(): void
    {
        $builder = $this->getUrlPdfBuilder();
        $builder->url('https://google.com');

        $multipartFormData = $builder->getMultipartFormData();

        self::assertCount(1, $multipartFormData);
        self::assertArrayHasKey(0, $multipartFormData);
        self::assertSame(['url' => 'https://google.com'], $multipartFormData[0]);
    }

    public function testCanProvideRoute(): void
    {
        $routeCollection = new RouteCollection();
        $routeCollection->add('fake_route', new Route('/route'));
        $urlGenerator = new UrlGenerator($routeCollection, new RequestContext());

        $builder = $this->getUrlPdfBuilder(urlGenerator: $urlGenerator);
        $builder->route('fake_route');

        $multipartFormData = $builder->getMultipartFormData();

        self::assertCount(1, $multipartFormData);
        self::assertArrayHasKey(0, $multipartFormData);
        self::assertSame(['url' => 'http://localhost/route'], $multipartFormData[0]);
    }

    public function testCanProvideRouteWithCustomContext(): void
    {
        $requestContext = new RequestContext();
        $requestContext->setHost('sensiolabs.com');

        $routeCollection = new RouteCollection();
        $routeCollection->add('fake_route', new Route('/route'));
        $urlGenerator = new UrlGenerator($routeCollection, new RequestContext());

        $originalRequestContext = $urlGenerator->getContext();

        $builder = $this->getUrlPdfBuilder(urlGenerator: $urlGenerator);
        $builder->setRequestContext($requestContext);

        $builder->route('fake_route');

        $multipartFormData = $builder->getMultipartFormData();

        self::assertCount(1, $multipartFormData);
        self::assertArrayHasKey(0, $multipartFormData);
        self::assertSame(['url' => 'http://sensiolabs.com/route'], $multipartFormData[0]);
        self::assertSame($originalRequestContext, $urlGenerator->getContext());
    }

    public function testRouterIsRequired(): void
    {
        $builder = $this->getUrlPdfBuilder();

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Router is required to use "Sensiolabs\GotenbergBundle\Builder\Pdf\UrlPdfBuilder::route" method. Try to run "composer require symfony/routing".');

        $builder->route('fake_route');
    }

    public function testRequiredEitherUrlOrRoute(): void
    {
        $builder = $this->getUrlPdfBuilder();

        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('URL (or route) is required');

        $builder->getMultipartFormData();
    }

    public function testRequiredEitherUrlOrRouteNotBoth(): void
    {
        $routeCollection = new RouteCollection();
        $routeCollection->add('fake_route', new Route('/route'));
        $urlGenerator = new UrlGenerator($routeCollection, new RequestContext());

        $builder = $this->getUrlPdfBuilder(urlGenerator: $urlGenerator);
        $builder->url('https://sensiolabs.com');
        $builder->route('fake_route');

        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('Provide only one of ["route", "url"] parameter. Not both.');

        $builder->getMultipartFormData();
    }

    private function getUrlPdfBuilder(UrlGeneratorInterface|null $urlGenerator = null): UrlPdfBuilder
    {
        return (new UrlPdfBuilder($this->gotenbergClient, self::$assetBaseDirFormatter, new RequestStack(), null, $urlGenerator, $this->webhookConfigurationRegistry))
            ->processor(new NullProcessor())
        ;
    }
}
