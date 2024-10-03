<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\Client\BodyBag;
use Sensiolabs\GotenbergBundle\Enumeration\PdfFormat;
use Sensiolabs\GotenbergBundle\Exception\JsonEncodingException;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @see https://gotenberg.dev/docs/routes#pdfa-chromium
 */
trait MetadataTrait
{
    abstract protected function getBodyBag(): BodyBag;

    protected function configure(OptionsResolver $bodyOptionsResolver, OptionsResolver $headersOptionsResolver): void
    {
        $bodyOptionsResolver
            ->define('metadata')
            ->info('Add metadata.')
            ->normalize(NormalizerFactory::json())
        ;
    }

    /**
     * Resets the metadata.
     *
     * @see https://gotenberg.dev/docs/routes#metadata-chromium
     * @see https://gotenberg.dev/docs/routes#metadata-libreoffice
     * @see https://gotenberg.dev/docs/routes#write-pdf-metadata-route
     * @see https://gotenberg.dev/docs/routes#merge-pdfs-route
     * @see https://exiftool.org/TagNames/XMP.html#pdf
     *
     * @param array<string, mixed> $metadata
     */
    public function metadata(array $metadata): static
    {
        $this->getBodyBag()->set('metadata', $metadata);

        return $this;
    }

    /**
     * The metadata to write.
     */
    public function addMetadata(string $key, string $value): static
    {
        $this->getBodyBag()->set('metadata', [$key => $value] + $this->getBodyBag()->get('metadata', []));

        return $this;
    }
}
