<?php

namespace Sensiolabs\GotenbergBundle\PayloadResolver\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\UrlGeneratorAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\HeadersBag;
use Sensiolabs\GotenbergBundle\PayloadResolver\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\Enumeration\SplitMode;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

trait WebhookOptionsTrait
{
    abstract protected function getHeadersOptionsResolver(): OptionsResolver;

    protected function configureOptions(): void
    {
        $this->getHeadersOptionsResolver()
            ->define('Gotenberg-Webhook-Url')
            ->info('The callback to use.')
            ->allowedValues('string')
        ;
        $this->getHeadersOptionsResolver()
            ->define('Gotenberg-Webhook-Method')
            ->info('The HTTP method to use (POST, PATCH, or PUT).')
            ->allowedValues(['POST', 'PATCH', 'PUT'])
        ;
        $this->getHeadersOptionsResolver()
            ->define('Gotenberg-Webhook-Error-Url')
            ->info('The callback to use if error.')
            ->allowedTypes('string')
        ;
        $this->getHeadersOptionsResolver()
            ->define('Gotenberg-Webhook-Error-Method')
            ->info('The HTTP method to use if error (POST, PATCH, or PUT).')
            ->allowedValues(['POST', 'PATCH', 'PUT'])
        ;
        $this->getHeadersOptionsResolver()
            ->define('Gotenberg-Webhook-Error-Extra-Http-Headers')
            ->info('The extra HTTP headers to send to both URLs.')
            ->allowedTypes('string[]')
            ->normalize(NormalizerFactory::json())
        ;
    }
}
