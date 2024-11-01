<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Screenshot;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject\MockObject;
use Sensiolabs\GotenbergBundle\Builder\GotenbergFileResult;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\AbstractChromiumScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\AbstractScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\UrlScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Processor\NullProcessor;
use Sensiolabs\GotenbergBundle\Tests\Builder\AbstractBuilderTestCase;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

#[CoversClass(UrlScreenshotBuilder::class)]
#[UsesClass(AbstractChromiumScreenshotBuilder::class)]
#[UsesClass(AbstractScreenshotBuilder::class)]
#[UsesClass(GotenbergFileResult::class)]
final class UrlScreenshotBuilderTest extends AbstractBuilderTestCase
{
    /**
     * @var MockObject&RouterInterface
     */
    protected RouterInterface $router;

    protected function setUp(): void
    {
        parent::setUp();
        $this->router = $this->createMock(RouterInterface::class);
    }

    public function testEndpointIsCorrect(): void
    {
        $this->gotenbergClient
            ->expects($this->once())
            ->method('call')
            ->with(
                $this->equalTo('/forms/chromium/screenshot/url'),
                $this->anything(),
                $this->anything(),
            )
        ;

        $this->getUrlScreenshotBuilder()
            ->url('https://google.com')
            ->generate()
        ;
    }

    public function testUrl(): void
    {
        $builder = $this->getUrlScreenshotBuilder();
        $builder->url('https://google.com');

        $multipartFormData = $builder->getMultipartFormData();

        self::assertCount(1, $multipartFormData);
        self::assertArrayHasKey(0, $multipartFormData);
        self::assertSame(['url' => 'https://google.com'], $multipartFormData[0]);
    }

    public function testRequiredFormData(): void
    {
        $builder = $this->getUrlScreenshotBuilder();

        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('URL (or route) is required');

        $builder->getMultipartFormData();
    }

    private function getUrlScreenshotBuilder(bool $twig = true): UrlScreenshotBuilder
    {
        return (new UrlScreenshotBuilder(
            $this->gotenbergClient,
            self::$assetBaseDirFormatter,
            $this->webhookConfigurationRegistry,
            new RequestStack(),
            true === $twig ? self::$twig : null,
            $this->router,
        ))
            ->processor(new NullProcessor())
        ;
    }
}
