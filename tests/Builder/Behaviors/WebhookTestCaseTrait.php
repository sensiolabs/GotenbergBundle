<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\Attributes\ExposeSemantic;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\UrlGeneratorAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\HeadersBag;
use Sensiolabs\GotenbergBundle\Enumeration\SplitMode;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Sensiolabs\GotenbergBundle\NodeBuilder\ArrayNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\EnumNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\ScalarNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\VariableNodeBuilder;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @template T of BuilderInterface
 */
trait WebhookTestCaseTrait
{
    /** @use BehaviorTrait<T> */
    use BehaviorTrait;

    abstract protected function assertGotenbergHeader(string $name, mixed $value): void;

    public function testAddFullWebhookConfiguration(): void
    {
        $this->getDefaultBuilder()
            ->webhook([
                'config_name' => 'my_config',
                'success' => [
                    'url' => 'http://example.com/success',
                    'method' => 'PUT',
                ],
                'error' => [
                    'url' => 'http://example.com/error',
                    'method' => 'POST',
                ],
                'extra_http_headers' => [
                    'my_header' => 'value',
                ],
            ])
            ->generate()
        ;

        $this->assertGotenbergHeader('Gotenberg-Webhook-Url', 'http://example.com/success');
        $this->assertGotenbergHeader('Gotenberg-Webhook-Method', 'PUT');
        $this->assertGotenbergHeader('Gotenberg-Webhook-Error-Url', 'http://example.com/error');
        $this->assertGotenbergHeader('Gotenberg-Webhook-Error-Method', 'POST');
        $this->assertGotenbergHeader('Gotenberg-Webhook-Extra-Http-Headers', '{"my_header":"value"}');
    }

    public function testAddWebhookUrlToCallOnSuccessResult(): void
    {
        $this->getDefaultBuilder()
            ->webhookUrl('http://example.com/success', 'PUT')
            ->generate()
        ;

        $this->assertGotenbergHeader('Gotenberg-Webhook-Url', 'http://example.com/success');
        $this->assertGotenbergHeader('Gotenberg-Webhook-Method', 'PUT');
    }

    public function testAddWebhookUrlToCallOnErrorResult(): void
    {
        $this->getDefaultBuilder()
            ->webhookErrorUrl('http://example.com/error', 'POST')
            ->generate()
        ;

        $this->assertGotenbergHeader('Gotenberg-Webhook-Error-Url', 'http://example.com/error');
        $this->assertGotenbergHeader('Gotenberg-Webhook-Error-Method', 'POST');
    }

    public function testAddWebhookExtraHeaders(): void
    {
        $this->getDefaultBuilder()
            ->webhookExtraHeaders(['my_header' => 'value'])
            ->generate()
        ;

        $this->assertGotenbergHeader('Gotenberg-Webhook-Extra-Http-Headers', '{"my_header":"value"}');
    }
}
