<?php

namespace Sensiolabs\GotenbergBundle\Builder\Screenshot;

use Sensiolabs\GotenbergBundle\Builder\AsyncBuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\AsyncBuilderTrait;
use Sensiolabs\GotenbergBundle\Builder\DefaultBuilderTrait;
use Sensiolabs\GotenbergBundle\Builder\DownloadFromTrait;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Sensiolabs\GotenbergBundle\Webhook\WebhookConfigurationRegistryInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class AbstractScreenshotBuilder implements ScreenshotBuilderInterface, AsyncBuilderInterface
{
    use AsyncBuilderTrait;
    use DefaultBuilderTrait;
    use DownloadFromTrait;

    public function __construct(
        GotenbergClientInterface $gotenbergClient,
        AssetBaseDirFormatter $asset,
        WebhookConfigurationRegistryInterface $webhookConfigurationRegistry,
        UrlGeneratorInterface|null $urlGenerator = null,
    ) {
        $this->client = $gotenbergClient;
        $this->asset = $asset;
        $this->webhookConfigurationRegistry = $webhookConfigurationRegistry;
        $this->urlGenerator = $urlGenerator;

        $this->normalizers = [
            'downloadFrom' => fn (array $value): array => $this->downloadFromNormalizer($value, $this->encodeData(...)),
        ];
    }

    /**
     * @param list<array{url: string, extraHttpHeaders: array<string, string>}> $downloadFrom
     */
    public function downloadFrom(array $downloadFrom): static
    {
        return $this->withDownloadFrom($this->formFields, $downloadFrom);
    }
}
