<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Enumeration\SplitMode;

/**
 * @see https://gotenberg.dev/docs/routes#split-chromium
 */
trait SplitTrait
{
    abstract protected function getBodyBag(): BodyBag;

    /**
     * Either intervals or pages. (default None).
     */
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
    public function splitSpan(string $splitSpan): self
    {
        $this->getBodyBag()->set('splitSpan', $splitSpan);

        return $this;
    }

    /**
     * Specify whether to put extracted pages into a single file or as many files as there are page ranges. Only works with pages mode. (default false).
     */
    public function splitUnify(bool $bool = true): self
    {
        $this->getBodyBag()->set('splitUnify', $bool);

        return $this;
    }

}
