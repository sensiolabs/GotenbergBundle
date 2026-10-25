<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Pdf\UrlPdfBuilder;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Test\Builder\GotenbergBuilderTestCase;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\ChromiumPdfTestCaseTrait;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * @extends GotenbergBuilderTestCase<UrlPdfBuilder>
 */
final class UrlPdfBuilderTest extends GotenbergBuilderTestCase
{
    /** @use ChromiumPdfTestCaseTrait<UrlPdfBuilder> */
    use ChromiumPdfTestCaseTrait;

    protected function createBuilder(): UrlPdfBuilder
    {
        return new UrlPdfBuilder();
    }

    /**
     * @param UrlPdfBuilder $builder
     */
    protected function initializeBuilder(BuilderInterface $builder, Container $container): UrlPdfBuilder
    {
        if (!$this->container->has('router')) {
            $this->container->set('router', new UrlGenerator(new RouteCollection(), new RequestContext()));
        }

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
        $this->container->set('router', new UrlGenerator(new RouteCollection(), new RequestContext()));

        $this->getBuilder()
            ->url('https://example.com')
            ->filename('test')
            ->generate()
        ;

        $this->assertGotenbergEndpoint('/forms/chromium/convert/url');
        $this->assertGotenbergHeader('Gotenberg-Output-Filename', 'test');
        $this->assertGotenbergFormData('url', 'https://example.com');
    }

    public function testToGenerateWithRequestContext(): void
    {
        $routeCollection = new RouteCollection();
        $routeCollection->add('article_read', new Route('/article/{id}', methods: Request::METHOD_GET));

        $requestContext = new RequestContext();
        $this->container->set('router', new UrlGenerator($routeCollection, $requestContext));

        $requestContext->setHost('example');

        $this->container->set('.sensiolabs_gotenberg.request_context', $requestContext);

        $this->getBuilder()
            ->route('article_read', ['id' => 1])
            ->filename('article')
            ->generate()
        ;

        $this->assertGotenbergEndpoint('/forms/chromium/convert/url');
        $this->assertGotenbergHeader('Gotenberg-Output-Filename', 'article');
        $this->assertGotenbergFormData('url', 'http://example/article/1');
    }

    public function testPdfGenerationFromAGivenRoute(): void
    {
        $routeCollection = new RouteCollection();
        $routeCollection->add('article_read', new Route('/article/{id}', methods: Request::METHOD_GET));

        $this->container->set('router', new UrlGenerator($routeCollection, new RequestContext()));

        $this->getBuilder()
            ->route('article_read', ['id' => 1])
            ->filename('article')
            ->generate()
        ;

        $this->assertGotenbergEndpoint('/forms/chromium/convert/url');
        $this->assertGotenbergHeader('Gotenberg-Output-Filename', 'article');
        $this->assertGotenbergFormData('url', 'http://localhost/article/1');
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

    public function testUrlGeneratorDependencyRequirement(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('UrlGenerator is required to use "Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\UrlGeneratorAwareTrait::getUrlGenerator" method. Try to run "composer require symfony/routing".');

        $routeCollection = new RouteCollection();
        $routeCollection->add('article_read', new Route('/article/{id}', methods: Request::METHOD_GET));

        $this->getBuilder()
            ->route('article_read', ['id' => 1])
            ->generate()
        ;
    }
}
