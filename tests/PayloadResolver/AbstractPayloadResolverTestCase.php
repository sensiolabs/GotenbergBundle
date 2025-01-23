<?php

namespace Sensiolabs\GotenbergBundle\Tests\PayloadResolver;

use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\HeadersBag;
use Sensiolabs\GotenbergBundle\PayloadResolver\PayloadResolverInterface;
use Sensiolabs\GotenbergBundle\PayloadResolver\Pdf\HtmlPdfPayloadResolver;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;

/**
 * @template T of PayloadResolverInterface
 */
#[UsesClass(BodyBag::class)]
#[UsesClass(HeadersBag::class)]
abstract class AbstractPayloadResolverTestCase extends TestCase
{
    protected const GOTENBERG_API_VERSION = '8.15.3';

    /** @var T */
    protected PayloadResolverInterface $resolver;

    protected function setUp(): void
    {
        parent::setUp();

        $this->resolver = $this->createResolver(self::GOTENBERG_API_VERSION);
    }

    /**
     * @return T
     */
    abstract protected function createResolver(string $gotenbergApiVersion): PayloadResolverInterface;

    /**
     * @return T
     */
    protected function getResolver(): PayloadResolverInterface
    {
        return $this->resolver;
    }

    public function testResolveBodyWithUndefinedOption(): void
    {
        self::expectException(UndefinedOptionsException::class);

        $htmlPdfPayloadResolver = $this->getResolver();
        $htmlPdfPayloadResolver->resolveBody((new BodyBag())->set('undefinedOptions', true));
    }

    public function testResolveHeadersWithUndefinedOption(): void
    {
        self::expectException(UndefinedOptionsException::class);

        $htmlPdfPayloadResolver = $this->getResolver();
        $htmlPdfPayloadResolver->resolveHeaders((new HeadersBag())->set('undefinedOptions', true));
    }
}
