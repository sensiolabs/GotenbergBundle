<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Chromium;

use Sensiolabs\GotenbergBundle\Builder\Attributes\ExposeSemantic;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\Util\ValidatorFactory;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;

/**
 * @see https://gotenberg.dev/docs/routes#wait-before-rendering-chromium.
 */
trait WaitBeforeRenderingTrait
{
    abstract protected function getBodyBag(): BodyBag;

    /**
     * Sets the duration (i.e., "1s", "2ms", etc.) to wait when loading an HTML
     * document before converting it to PDF. (default None).
     *
     * @see https://gotenberg.dev/docs/routes#wait-before-rendering-chromium
     */
    #[ExposeSemantic('wait_delay', options: ['default_null' => true])]
    public function waitDelay(string $delay): static
    {
        if (!ValidatorFactory::waitDelay($delay)) {
            throw new InvalidBuilderConfiguration(\sprintf('Invalid value "%s" for "waitDelay".', $delay));
        }

        $this->getBodyBag()->set('waitDelay', $delay);

        return $this;
    }

    /**
     * Sets the JavaScript expression to wait before converting an HTML
     * document to PDF until it returns true. (default None).
     *
     * For instance: "window.status === 'ready'".
     *
     * @see https://gotenberg.dev/docs/routes#wait-before-rendering
     */
    #[ExposeSemantic('wait_for_expression', options: ['default_null' => true])]
    public function waitForExpression(string $expression): static
    {
        $this->getBodyBag()->set('waitForExpression', $expression);

        return $this;
    }
}
