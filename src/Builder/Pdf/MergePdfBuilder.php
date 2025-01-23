<?php

namespace Sensiolabs\GotenbergBundle\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\AbstractBuilder;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\AssetBaseDirFormatterAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\DownloadFromTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\MetadataTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\PdfFormatTrait;

/**
 * @see https://gotenberg.dev/docs/routes#merge-pdfs-route
 */
class MergePdfBuilder extends AbstractBuilder
{
    use AssetBaseDirFormatterAwareTrait;
    use DownloadFromTrait;
    use MetadataTrait;
    use PdfFormatTrait;

    protected function getEndpoint(): string
    {
        return '/forms/pdfengines/merge';
    }

    /**
     * Add PDF files to merge.
     *
     * @see https://gotenberg.dev/docs/routes#merge-pdfs-route
     */
    public function files(string ...$paths): self
    {
        $this->getBodyBag()->set('files', array_map(
            fn (string $path): \SplFileInfo => new \SplFileInfo($this->getAssetBaseDirFormatter()->resolve($path)),
            $paths,
        ));

        return $this;
    }
}
