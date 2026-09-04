<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\UrlScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Test\Builder\GotenbergBuilderTestCase;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\ChromiumScreenshotTestCaseTrait;
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

        $this->container->set('router', new UrlGenerator(new RouteCollection(), new RequestContext()));
    }

    protected function createBuilder(): UrlScreenshotBuilder
    {
        return new UrlScreenshotBuilder();
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
        $this->container->set('router', new UrlGenerator(new RouteCollection(), new RequestContext()));

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

        $this->container->set('router', new UrlGenerator($routeCollection, new RequestContext()));

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
        $this->container->set('router', new UrlGenerator($routeCollection, new RequestContext()));

        $requestContext->setHost('example');

        $this->container->set('.sensiolabs_gotenberg.request_context', $requestContext);

        $this->getBuilder()
            ->route('article_read', ['id' => 1])
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

    /**
     * Deprecated since 1.5, to be removed in 2.0 along with the content*() methods of this builder.
     */
    public function testContentIsDeprecatedButStillSent(): void
    {
        $deprecations = $this->collectDeprecations(function (): void {
            $this->getDefaultBuilder()
                ->contentFile('files/content.html')
                ->contentRaw('<h2>The content</h2>')
                ->generate()
            ;
        });

        self::assertSame([
            'Since sensiolabs/gotenberg-bundle 1.5: Calling "Sensiolabs\\GotenbergBundle\\Builder\\Screenshot\\UrlScreenshotBuilder::contentFile()" is deprecated, the page body comes from the URL. Use "url()" or "route()" instead. It will be removed in 2.0.',
            'Since sensiolabs/gotenberg-bundle 1.5: Calling "Sensiolabs\\GotenbergBundle\\Builder\\Screenshot\\UrlScreenshotBuilder::contentRaw()" is deprecated, the page body comes from the URL. Use "url()" or "route()" instead. It will be removed in 2.0.',
        ], $deprecations);

        $this->assertContentFile('index.html', 'text/html', '<h2>The content</h2>');
    }
}
