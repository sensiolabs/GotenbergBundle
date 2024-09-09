<?php

namespace Sensiolabs\GotenbergBundle\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\AsyncBuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\AsyncBuilderTrait;
use Sensiolabs\GotenbergBundle\Builder\DefaultBuilderTrait;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\DependencyInjection\WebhookConfiguration\WebhookConfigurationRegistryInterface;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;

abstract class AbstractPdfBuilder implements PdfBuilderInterface, AsyncBuilderInterface
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
            'metadata' => function (mixed $value): array {
                return $this->encodeData('metadata', $value);
            },
        ];
    }
}
