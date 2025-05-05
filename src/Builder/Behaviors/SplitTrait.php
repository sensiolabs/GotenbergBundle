<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\Attributes\ExposeSemantic;
use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\Builder\Util\ValidatorFactory;
use Sensiolabs\GotenbergBundle\Enumeration\SplitMode;
use Sensiolabs\GotenbergBundle\NodeBuilder\BooleanNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\NativeEnumNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\ScalarNodeBuilder;

/**
 * @see https://gotenberg.dev/docs/routes#split-chromium
 * @see https://gotenberg.dev/docs/routes#split-libreoffice
 */
trait SplitTrait
{
    abstract protected function getBodyBag(): BodyBag;

    /**
     * Either intervals or pages.
     */
    #[ExposeSemantic(new NativeEnumNodeBuilder('split_mode', enumClass: SplitMode::class))]
    public function splitMode(SplitMode|null $splitMode = null): self
    {
        if (!$splitMode) {
            $this->getBodyBag()->unset('splitMode');
        } else {
            $this->getBodyBag()->set('splitMode', $splitMode);
        }

        return $this;
    }

    /**
     * Either the intervals or the page ranges to extract, depending on the selected mode.
     */
    #[ExposeSemantic(new ScalarNodeBuilder('split_span'))]
    public function splitSpan(string $splitSpan): self
    {
        ValidatorFactory::splitSpan($splitSpan);
        $this->getBodyBag()->set('splitSpan', $splitSpan);

        return $this;
    }

    /**
     * Specify whether to put extracted pages into a single file or as many files as there are page ranges. Only works with pages mode. (default false).
     */
    #[ExposeSemantic(new BooleanNodeBuilder('split_unify'))]
    public function splitUnify(bool $bool = true): self
    {
        $this->getBodyBag()->set('splitUnify', $bool);

        return $this;
    }

    #[NormalizeGotenbergPayload]
    private function normalizeSplit(): \Generator
    {
        yield 'splitMode' => NormalizerFactory::enum();
        yield 'splitUnify' => NormalizerFactory::bool();
    }
}
