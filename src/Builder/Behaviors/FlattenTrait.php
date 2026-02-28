<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\Attributes\WithConfigurationNode;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\LoggerAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\NodeBuilder\BooleanNodeBuilder;

trait FlattenTrait
{
    use LoggerAwareTrait;

    abstract protected function getBodyBag(): BodyBag;

    /**
     * Flattening a PDF combines all its contents into a single layer. (default false).
     *
     * @see https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#flatten-pdf-engines
     *
     * @example flatten() // is same as `->flatten(true)`
     */
    #[WithConfigurationNode(new BooleanNodeBuilder('flatten'))]
    public function flatten(bool $bool = true): self
    {
        $this->logWarningIfVersionIs('<', '8.16', 'The flatten option is not available.');

        $this->getBodyBag()->set('flatten', $bool);

        return $this;
    }

    #[NormalizeGotenbergPayload]
    private function normalizeFlatten(): \Generator
    {
        yield 'flatten' => NormalizerFactory::bool();
    }
}
