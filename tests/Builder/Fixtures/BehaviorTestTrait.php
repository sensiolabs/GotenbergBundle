<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Fixtures;

use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergHeaders;
use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\HeadersBag;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;

trait BehaviorTestTrait
{
    abstract protected function getBodyBag(): BodyBag;

    abstract protected function getHeadersBag(): HeadersBag;

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

    public function enableHeaderFeature(): static
    {
        $this->getHeadersBag()->set('Gotenberg-Feature', true);

        return $this;
    }

    #[NormalizeGotenbergHeaders]
    private function normalizeHeaderFeature(): \Generator
    {
        yield 'Gotenberg-Feature' => static function (string $key, bool $value): \Generator {
            yield [$key => $value ? 'true' : 'false'];
        };
    }
}
