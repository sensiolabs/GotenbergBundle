<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\Attributes\ExposeSemantic;
use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\Builder\Util\ValidatorFactory;
use Sensiolabs\GotenbergBundle\Enumeration\NodeType;
use Sensiolabs\GotenbergBundle\Enumeration\SplitMode;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;

/**
 * @see https://gotenberg.dev/docs/routes#split-chromium
 */
trait SplitTrait
{
    abstract protected function getBodyBag(): BodyBag;

    /**
     * Either intervals or pages. (default None).
     */
    #[ExposeSemantic('split_mode', NodeType::Enum, ['default_null' => true, 'class' => SplitMode::class, 'callback' => [SplitMode::class, 'cases']])]
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
     * Either the intervals or the page ranges to extract, depending on the selected mode. (default None).
     */
    #[ExposeSemantic('split_span', options: ['default_null' => true])]
    public function splitSpan(string $splitSpan): self
    {
        if (!ValidatorFactory::splitSpan($splitSpan)) {
            throw new InvalidBuilderConfiguration('Invalid value, the range value format need to look like e.g 1-20 or as a single int value e.g 2.');
        }
        $this->getBodyBag()->set('splitSpan', $splitSpan);

        return $this;
    }

    /**
     * Specify whether to put extracted pages into a single file or as many files as there are page ranges. Only works with pages mode. (default false).
     */
    #[ExposeSemantic('split_unify', NodeType::Boolean, ['default_null' => true])]
    public function splitUnify(bool $bool = true): self
    {
        $this->getBodyBag()->set('splitUnify', $bool);

        return $this;
    }

    #[NormalizeGotenbergPayload]
    protected function normalizeSplit(): \Generator
    {
        yield 'splitMode' => NormalizerFactory::enum();
        yield 'splitUnify' => NormalizerFactory::bool();
    }
}
