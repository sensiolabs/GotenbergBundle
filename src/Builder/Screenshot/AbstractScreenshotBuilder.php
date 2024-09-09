<?php

namespace Sensiolabs\GotenbergBundle\Builder\Screenshot;

use Sensiolabs\GotenbergBundle\Builder\AsyncBuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\AsyncBuilderTrait;
use Sensiolabs\GotenbergBundle\Builder\DefaultBuilderTrait;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\DependencyInjection\WebhookConfiguration\WebhookConfigurationRegistryInterface;
use Sensiolabs\GotenbergBundle\Enumeration\Part;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Symfony\Component\Mime\Part\DataPart;

abstract class AbstractScreenshotBuilder implements ScreenshotBuilderInterface, AsyncBuilderInterface
{
    use AsyncBuilderTrait;
    use DefaultBuilderTrait;

    public function __construct(
        GotenbergClientInterface $gotenbergClient,
        AssetBaseDirFormatter $asset,
        WebhookConfigurationRegistryInterface $webhookConfigurationRegistry,
    ) {
        $this->client = $gotenbergClient;
        $this->asset = $asset;
        $this->webhookConfigurationRegistry = $webhookConfigurationRegistry;

        $this->normalizers = [
            'extraHttpHeaders' => function (mixed $value): array {
                return $this->encodeData('extraHttpHeaders', $value);
            },
            'assets' => static function (array $value): array {
                return ['files' => $value];
            },
            Part::Body->value => static function (DataPart $value): array {
                return ['files' => $value];
            },
            'failOnHttpStatusCodes' => function (mixed $value): array {
                return $this->encodeData('failOnHttpStatusCodes', $value);
            },
            'cookies' => function (mixed $value): array {
                return $this->encodeData('cookies', array_values($value));
            },
        ];
    }
}
