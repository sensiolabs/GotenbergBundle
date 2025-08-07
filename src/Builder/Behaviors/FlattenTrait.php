<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\Attributes\WithConfigurationNode;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\LoggerAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\NodeBuilder\BooleanNodeBuilder;
use Sensiolabs\GotenbergBundle\Version\Version;

/**
 * @see https://gotenberg.dev/docs/routes#flatten-libreoffice
 */
trait FlattenTrait
{
    abstract protected function getBodyBag(): BodyBag;
    abstract protected function getVersion(): Version;

    use LoggerAwareTrait;

    /**
     * Flattening a PDF combines all its contents into a single layer. (default false).
     */
    #[WithConfigurationNode(new BooleanNodeBuilder('flatten'))]
    public function flatten(bool $bool = true): self
    {
        if ($this->getVersion()->isLowerThan('8.16.0')) {
            $this->getLogger()?->warning('The flatten option is only available from version Gotenberg >= 8.16.0.');
        }

        $this->getBodyBag()->set('flatten', $bool);

        return $this;
    }

    #[NormalizeGotenbergPayload]
    private function normalizeFlatten(): \Generator
    {
        yield 'flatten' => NormalizerFactory::bool();
    }
}
