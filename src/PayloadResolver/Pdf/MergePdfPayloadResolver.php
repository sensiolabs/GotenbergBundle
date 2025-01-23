<?php

namespace Sensiolabs\GotenbergBundle\PayloadResolver\Pdf;

use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\HeadersBag;
use Sensiolabs\GotenbergBundle\Builder\Util\ValidatorFactory;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\PayloadResolver\AbstractPayloadResolver;
use Sensiolabs\GotenbergBundle\PayloadResolver\Behaviors\DownloadFromOptionsTrait;
use Sensiolabs\GotenbergBundle\PayloadResolver\Behaviors\MetadataOptionsTrait;
use Sensiolabs\GotenbergBundle\PayloadResolver\Behaviors\PdfFormatOptionsTrait;

final class MergePdfPayloadResolver extends AbstractPayloadResolver
{
    use DownloadFromOptionsTrait { DownloadFromOptionsTrait::configureOptions as configureDownloadFromOptions; }
    use MetadataOptionsTrait { MetadataOptionsTrait::configureOptions as configureMetadataOptions; }
    use PdfFormatOptionsTrait { PdfFormatOptionsTrait::configureOptions as configurePdfFormatOptions; }

    public function resolveBody(BodyBag $bodyBag): array
    {
        $resolvedData = $this->getBodyOptionsResolver()->resolve($bodyBag->all());

        if (!\array_key_exists('files', $resolvedData) && [] === ($resolvedData['downloadFrom'] ?? [])) {
            throw new MissingRequiredFieldException('At least one PDF file is required');
        }

        return $resolvedData;
    }

    public function resolveHeaders(HeadersBag $headersBag): array
    {
        return $this->getHeadersOptionsResolver()->resolve($headersBag->all());
    }

    protected function configureOptions(): void
    {
        $this->configureDownloadFromOptions();
        $this->configureMetadataOptions();
        $this->configurePdfFormatOptions();

        $this->getBodyOptionsResolver()
            ->define('files')
            ->info('Add PDF files to merge')
            ->allowedValues(ValidatorFactory::filesExtension())
        ;
    }
}
