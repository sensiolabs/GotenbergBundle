<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Pdf\UrlPdfBuilder;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\ChromiumTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\GotenbergBuilderTestCase;
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
    /** @use ChromiumTestCaseTrait<UrlPdfBuilder> */
    use ChromiumTestCaseTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dependencies->set('router', new UrlGenerator(new RouteCollection(), new RequestContext()));
    }

    protected function createBuilder(GotenbergClientInterface $client, Container $dependencies): UrlPdfBuilder
    {
        return new UrlPdfBuilder($client, $dependencies);
    }

    /**
     * @param UrlPdfBuilder $builder
     */
    protected function initializeBuilder(BuilderInterface $builder, Container $container): UrlPdfBuilder
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

    public function testFilename(): void
    {
        $this->dependencies->set('router', new UrlGenerator(new RouteCollection(), new RequestContext()));

        $this->getBuilder()
            ->url('https://example.com')
            ->filename('test')
            ->generate()
        ;

        $this->assertGotenbergEndpoint('/forms/chromium/convert/url');
        $this->assertGotenbergHeader('Gotenberg-Output-Filename', 'test');
        $this->assertGotenbergFormData('url', 'https://example.com');
    }

    public function testWithRoute(): void
    {
        $routeCollection = new RouteCollection();
        $routeCollection->add('article_read', new Route('/article/{id}', methods: Request::METHOD_GET));

        $this->dependencies->set('router', new UrlGenerator($routeCollection, new RequestContext()));

        $this->getBuilder()
            ->route('article_read', ['id' => 1])
            ->filename('article')
            ->generate()
        ;

        $this->assertGotenbergEndpoint('/forms/chromium/convert/url');
        $this->assertGotenbergHeader('Gotenberg-Output-Filename', 'article');
        $this->assertGotenbergFormData('url', 'http://localhost/article/1');
    }

    public function testDownloadFrom(): void
    {
        $this->dependencies->set('router', new UrlGenerator(new RouteCollection(), new RequestContext()));

        $this->getBuilder()
            ->url('https://example.com')
            ->downloadFrom([
                [
                    'url' => 'http://url/to/file.com',
                    'extraHttpHeaders' => ['MyHeader' => 'MyValue', 'User-Agent' => 'MyValue'],
                ],
            ])
            ->generate()
        ;

        $this->assertGotenbergFormData('downloadFrom', '[{"url":"http:\/\/url\/to\/file.com","extraHttpHeaders":{"MyHeader":"MyValue","User-Agent":"MyValue"}}]');
    }
}
