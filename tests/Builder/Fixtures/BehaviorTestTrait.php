<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Fixtures;

use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;

trait BehaviorTestTrait
{
    abstract protected function getBodyBag(): BodyBag;

    public function setValue(bool $value): static
    {
        $this->getBodyBag()->set('key', $value);

        return $this;
    }

    #[NormalizeGotenbergPayload]
    private function normalizeValue(): \Generator
    {
        yield 'key' => NormalizerFactory::bool();
    }
}
