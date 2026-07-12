<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder;

use Sensiolabs\GotenbergBundle\Test\Builder\GotenbergBuilderTestCase;
use Sensiolabs\GotenbergBundle\Tests\Builder\Fixtures\ConcreteTestBuilder;

/**
 * @extends GotenbergBuilderTestCase<ConcreteTestBuilder>
 */
final class AbstractBuilderNormalizeTest extends GotenbergBuilderTestCase
{
    protected function createBuilder(): ConcreteTestBuilder
    {
        return new ConcreteTestBuilder();
    }

    public function testNormalizePayloadFromTraitUsedInAbstractParent(): void
    {
        $this->getBuilder()
            ->enableFeature()
            ->generate()
        ;

        $this->assertGotenbergFormData('feature', 'true');
    }

    public function testNormalizeHeadersFromTraitUsedInAbstractParent(): void
    {
        $this->getBuilder()
            ->enableHeaderFeature()
            ->generate()
        ;

        $this->assertGotenbergHeader('Gotenberg-Feature', 'true');
    }

    public function testNormalizeHeadersForAsyncRequest(): void
    {
        $this->getBuilder()
            ->enableHeaderFeature()
            ->generateAsync()
        ;

        $this->assertGotenbergHeader('Gotenberg-Feature', 'true');
    }
}
