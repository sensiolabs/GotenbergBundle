<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Client\BodyBag;
use Sensiolabs\GotenbergBundle\Enumeration\PdfFormat;
use Sensiolabs\GotenbergBundle\Enumeration\SplitMode;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @see https://gotenberg.dev/docs/routes#split-chromium
 */
trait SplitTrait
{
    abstract protected function getBodyBag(): BodyBag;

    protected function configure(OptionsResolver $bodyOptionsResolver, OptionsResolver $headersOptionsResolver): void
    {
        $bodyOptionsResolver
            ->define('splitMode')
            ->info('Either intervals or pages.')
            ->allowedValues(...SplitMode::cases())
        ;
        $bodyOptionsResolver
            ->define('splitSpan')
            ->info('Either the intervals or the page ranges to extract, depending on the selected mode.')
            ->allowedTypes('string')
        ;
        $bodyOptionsResolver
            ->define('splitUnify')
            ->info('Specify whether to put extracted pages into a single file or as many files as there are page ranges. Only works with pages mode.')
            ->allowedTypes('bool')
        ;
    }

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
