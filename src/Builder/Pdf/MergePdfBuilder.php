<?php

namespace Sensiolabs\GotenbergBundle\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\AbstractBuilder;
use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\Attributes\SemanticNode;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\AssetBaseDirFormatterAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\DownloadFromTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\FlattenTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\MetadataTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\PdfFormatTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\WebhookTrait;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\Builder\Util\ValidatorFactory;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;

/**
 * @see https://gotenberg.dev/docs/routes#merge-pdfs-route
 */
#[SemanticNode(type: 'pdf', name: 'merge')]
final class MergePdfBuilder extends AbstractBuilder
{
    use AssetBaseDirFormatterAwareTrait;
    use DownloadFromTrait;
    use FlattenTrait;
    use MetadataTrait;
    use PdfFormatTrait;
    use WebhookTrait;

    public const ENDPOINT = '/forms/pdfengines/merge';

    /**
     * Add PDF files to merge.
     *
     * @see https://gotenberg.dev/docs/routes#merge-pdfs-route
     */
    public function files(string|\Stringable ...$paths): self
    {
        foreach ($paths as $path) {
            $path = (string) $path;

            $info = new \SplFileInfo($this->getAssetBaseDirFormatter()->resolve($path));
            ValidatorFactory::filesExtension([$info], ['pdf']);

            $files[$path] = $info;
        }

        $this->getBodyBag()->set('files', $files ?? null);

        return $this;
    }

    protected function getEndpoint(): string
    {
        return self::ENDPOINT;
    }

    protected function validatePayloadBody(): void
    {
        if ($this->getBodyBag()->get('files') === null && $this->getBodyBag()->get('downloadFrom') === null) {
            throw new MissingRequiredFieldException('At least one PDF file is required.');
        }
    }

    #[NormalizeGotenbergPayload]
    private function normalizeFiles(): \Generator
    {
        yield 'files' => NormalizerFactory::asset();
    }
}
