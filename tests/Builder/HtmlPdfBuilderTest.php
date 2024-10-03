<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder;

use PHPUnit\Framework\Attributes\CoversClass;
use Psr\Container\ContainerInterface;
use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\HtmlPdfBuilder;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Tests\Builder\GotenbergBuilderTestCase;

/**
 * @extends GotenbergBuilderTestCase<HtmlPdfBuilder>
 */
#[CoversClass(HtmlPdfBuilder::class)]
class HtmlPdfBuilderTest extends GotenbergBuilderTestCase
{
    protected function createBuilder(GotenbergClientInterface $client, ContainerInterface $dependencies): BuilderInterface
    {
        return new HtmlPdfBuilder($client, $dependencies);
    }

    public function testFilename(): void
    {
        $this->getBuilder()
            ->filename('test')
            ->generate()
        ;

        $this->assertGotenbergEndpoint('/forms/chromium/convert/html');
        $this->assertGotenbergHeader('Gotenberg-Output-Filename', 'test');
    }
}
