<?php

namespace Sensiolabs\GotenbergBundle\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\DefaultBuilderTrait;
use Sensiolabs\GotenbergBundle\Builder\DownloadFromTrait;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;

abstract class AbstractPdfBuilder implements PdfBuilderInterface
{
    use DefaultBuilderTrait;
    use DownloadFromTrait;

    public function __construct(
        GotenbergClientInterface $gotenbergClient,
        AssetBaseDirFormatter $asset,
    ) {
        $this->client = $gotenbergClient;
        $this->asset = $asset;

        $this->normalizers = [
            'metadata' => function (mixed $value): array {
                return $this->encodeData('metadata', $value);
            },
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
