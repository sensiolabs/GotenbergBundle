<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\AssetBaseDirFormatterAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\DownloadFromTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\MetadataTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\PdfFormatTrait;
use Sensiolabs\GotenbergBundle\Builder\Util\ValidatorFactory;
use Sensiolabs\GotenbergBundle\Client\Payload;
use Sensiolabs\GotenbergBundle\Enumeration\Part;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @see https://gotenberg.dev/docs/routes#merge-pdfs-route
 */
class MergePdfBuilder extends AbstractBuilder
{
    use AssetBaseDirFormatterAwareTrait;
    use DownloadFromTrait { DownloadFromTrait::configure as configureDownloadFrom; }
    use MetadataTrait { MetadataTrait::configure as configureMetadata; }
    use PdfFormatTrait { PdfFormatTrait::configure as configurePdfFormat; }

    protected function getEndpoint(): string
    {
        return '/forms/pdfengines/merge';
    }

    protected function configure(OptionsResolver $bodyOptionsResolver, OptionsResolver $headersOptionsResolver): void
    {
        parent::configure($bodyOptionsResolver, $headersOptionsResolver);
        $this->configureMetadata($bodyOptionsResolver, $headersOptionsResolver);
        $this->configurePdfFormat($bodyOptionsResolver, $headersOptionsResolver);

        $bodyOptionsResolver
            ->define('files')
            ->info('Add PDF files to merge')
            ->allowedValues(ValidatorFactory::filesExtension())
        ;
    }

    protected function validatePayload(Payload $payload): void
    {
        $body = $payload->getPayloadBody();

        if ([] === ($body['files'] ?? []) && [] === ($body['downloadFrom'] ?? [])) {
            throw new MissingRequiredFieldException('At least one PDF file is required');
        }
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
