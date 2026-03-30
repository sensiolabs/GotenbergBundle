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
}
