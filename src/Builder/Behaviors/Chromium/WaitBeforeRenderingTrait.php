<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Chromium;

use Sensiolabs\GotenbergBundle\Builder\Attributes\WithConfigurationNode;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\Util\ValidatorFactory;
use Sensiolabs\GotenbergBundle\NodeBuilder\ScalarNodeBuilder;

/**
 * @package Behavior\\Chromium\\WaitFor
 */
trait WaitBeforeRenderingTrait
{
    abstract protected function getBodyBag(): BodyBag;

    /**
     * Sets the duration (i.e., "1s", "2ms", etc.) to wait when loading an HTML
     * document before converting it to PDF.
     *
     * @see https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#wait-delay
     *
     * @example waitDelay('5s')
     */
    #[WithConfigurationNode(new ScalarNodeBuilder('wait_delay'))]
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
     * @see https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#wait-for-expression
     *
     * @example waitForExpression("window.globalVar === 'ready'")
     */
    #[WithConfigurationNode(new ScalarNodeBuilder('wait_for_expression'))]
    public function waitForExpression(string $expression): static
    {
        $this->getBodyBag()->set('waitForExpression', $expression);

        return $this;
    }

    /**
     * Selector (e.g. '#id') to query before converting an HTML document into PDF until it matches a node.
     *
     * @see https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#wait-for-selector
     *
     * @example waitForSelector('#special-id')
     */
    #[WithConfigurationNode(new ScalarNodeBuilder('wait_for_selector', restrictTo: 'string'))]
    public function waitForSelector(string $selector): static
    {
        $this->logWarningIfVersionIs('<', '8.26', 'The option waitForSelector is not available.');

        $this->getBodyBag()->set('waitForSelector', $selector);

        return $this;
    }
}
