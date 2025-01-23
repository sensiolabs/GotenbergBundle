<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\BodyBag;

/**
 * @see https://gotenberg.dev/docs/routes#pdfa-chromium
 */
trait MetadataTrait
{
    abstract protected function getBodyBag(): BodyBag;

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
