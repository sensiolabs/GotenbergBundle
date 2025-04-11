<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Chromium;

use Sensiolabs\GotenbergBundle\Builder\Attributes\ExposeSemantic;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\Util\ValidatorFactory;
use Sensiolabs\GotenbergBundle\NodeBuilder\ScalarNodeBuilder;

trait WaitBeforeRenderingTrait
{
    abstract protected function getBodyBag(): BodyBag;

    /**
     * Sets the duration (i.e., "1s", "2ms", etc.) to wait when loading an HTML
     * document before converting it to PDF.
     *
     * @see https://gotenberg.dev/docs/routes#wait-before-rendering-chromium
     */
    #[ExposeSemantic(new ScalarNodeBuilder('wait_delay'))]
    public function waitDelay(string $delay): static
    {
        ValidatorFactory::waitDelay($delay);
        $this->getBodyBag()->set('waitDelay', $delay);

        return $this;
    }

    /**
     * Sets the JavaScript expression to wait before converting an HTML document to PDF until it returns true.
     *
     * For instance: "window.status === 'ready'".
     *
     * @see https://gotenberg.dev/docs/routes#wait-before-rendering
     */
    #[ExposeSemantic(new ScalarNodeBuilder('wait_for_expression'))]
    public function waitForExpression(string $expression): static
    {
        $this->getBodyBag()->set('waitForExpression', $expression);

        return $this;
    }
}
