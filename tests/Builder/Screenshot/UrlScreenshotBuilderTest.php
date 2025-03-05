<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\UrlScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\ChromiumScreenshotTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\GotenbergBuilderTestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * @extends GotenbergBuilderTestCase<UrlScreenshotBuilder>
 */
final class UrlScreenshotBuilderTest extends GotenbergBuilderTestCase
{
    /** @use ChromiumScreenshotTestCaseTrait<UrlScreenshotBuilder> */
    use ChromiumScreenshotTestCaseTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dependencies->set('router', new UrlGenerator(new RouteCollection(), new RequestContext()));
    }

    protected function createBuilder(GotenbergClientInterface $client, Container $dependencies): UrlScreenshotBuilder
    {
        return new UrlScreenshotBuilder($client, $dependencies);
    }

    /**
     * @param UrlScreenshotBuilder $builder
     */
    protected function initializeBuilder(BuilderInterface $builder, Container $container): UrlScreenshotBuilder
    {
        return $builder
            ->url('https://example.com')
        ;
    }

    public function testRequiredFormData(): void
    {
        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('"url" (or "route") is required');

        $this->getBuilder()
            ->generate()
        ;
    }

    public function testOutputFilename(): void
    {
        $this->dependencies->set('router', new UrlGenerator(new RouteCollection(), new RequestContext()));

        $this->getBuilder()
            ->url('https://example.com')
            ->filename('test')
            ->generate()
        ;

        $this->assertGotenbergEndpoint('/forms/chromium/screenshot/url');
        $this->assertGotenbergHeader('Gotenberg-Output-Filename', 'test');
        $this->assertGotenbergFormData('url', 'https://example.com');
    }

    public function testPdfGenerationFromAGivenRoute(): void
    {
        $routeCollection = new RouteCollection();
        $routeCollection->add('article_read', new Route('/article/{id}', methods: Request::METHOD_GET));

        $this->dependencies->set('router', new UrlGenerator($routeCollection, new RequestContext()));

        $this->getBuilder()
            ->route('article_read', ['id' => 1])
            ->filename('article')
            ->generate()
        ;

        $this->assertGotenbergEndpoint('/forms/chromium/screenshot/url');
        $this->assertGotenbergHeader('Gotenberg-Output-Filename', 'article');
        $this->assertGotenbergFormData('url', 'http://localhost/article/1');
    }

    public function testToGenerateWithRequestContext(): void
    {
        $routeCollection = new RouteCollection();
        $routeCollection->add('article_read', new Route('/article/{id}', methods: Request::METHOD_GET));

        $requestContext = new RequestContext();
        $this->dependencies->set('router', new UrlGenerator($routeCollection, new RequestContext()));

        $requestContext->setHost('example');

        $this->getBuilder()
            ->route('article_read', ['id' => 1])
            ->setRequestContext($requestContext)
            ->filename('article')
            ->generate()
        ;

        $this->assertGotenbergEndpoint('/forms/chromium/screenshot/url');
        $this->assertGotenbergHeader('Gotenberg-Output-Filename', 'article');
        $this->assertGotenbergFormData('url', 'http://example/article/1');
    }

    public function testRequirementAboutRouteAndUrlProvided(): void
    {
        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('Provide only one of ["route", "url"] parameter. Not both.');

        $this->getBuilder()
            ->url('https://example.com')
            ->route('article_read', ['id' => 1])
            ->filename('test')
            ->generate()
        ;
    }
}
