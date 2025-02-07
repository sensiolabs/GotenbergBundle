<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Pdf;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use Psr\Container\ContainerInterface;
use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Pdf\UrlPdfBuilder;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Sensiolabs\GotenbergBundle\Tests\Builder\GotenbergBuilderTestCase;
use Sensiolabs\GotenbergBundle\Twig\GotenbergAssetRuntime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\RuntimeLoader\RuntimeLoaderInterface;

/**
 * @extends GotenbergBuilderTestCase<UrlPdfBuilder>
 */
#[CoversClass(UrlPdfBuilder::class)]
#[UsesClass(UrlGenerator::class)]
#[UsesClass(RouteCollection::class)]
#[UsesClass(RequestContext::class)]
class UrlPdfBuilderTest extends GotenbergBuilderTestCase
{
    protected function createBuilder(GotenbergClientInterface $client, ContainerInterface $dependencies): BuilderInterface
    {
        return new UrlPdfBuilder($client, $dependencies);
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

        $this->builder
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

        $this->builder
            ->route('article_read', ['id' => 1])
            ->filename('article')
            ->generate()
        ;

        $this->assertGotenbergEndpoint('/forms/chromium/convert/url');
        $this->assertGotenbergHeader('Gotenberg-Output-Filename', 'article');
        $this->assertGotenbergFormData('url', 'http://localhost/article/1');
    }
}
