<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Fixtures;

use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;

trait BehaviorTestTrait
{
    abstract protected function getBodyBag(): BodyBag;

    public function enableFeature(): static
    {
        $this->getBodyBag()->set('feature', true);

        return $this;
    }

    #[NormalizeGotenbergPayload]
    private function normalizeFeature(): \Generator
    {
        yield 'feature' => NormalizerFactory::bool();
    }
}
