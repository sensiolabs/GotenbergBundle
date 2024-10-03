<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\Attributes\ExposeSemantic;
use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\NodeBuilder\BooleanNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\EnumNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\MetadataNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\ScalarNodeBuilder;

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
     * @param array{
     *     Author?: string,
     *     Copyright?: string,
     *     CreationDate?: string,
     *     Creator?: string,
     *     Keywords?: string,
     *     Marked?: bool,
     *     ModDate?: string,
     *     PDFVersion?: string,
     *     Producer?: string,
     *     Subject?: string,
     *     Title?: string,
     *     Trapped?: 'True'|'False'|'Unknown',
     * } $metadata
     */
    #[ExposeSemantic(new MetadataNodeBuilder('metadata', children: [
        new ScalarNodeBuilder('Author'),
        new ScalarNodeBuilder('Copyright'),
        new ScalarNodeBuilder('CreationDate'),
        new ScalarNodeBuilder('Creator'),
        new ScalarNodeBuilder('Keywords'),
        new BooleanNodeBuilder('Marked'),
        new ScalarNodeBuilder('ModDate'),
        new ScalarNodeBuilder('PDFVersion'),
        new ScalarNodeBuilder('Producer'),
        new ScalarNodeBuilder('Subject'),
        new ScalarNodeBuilder('Title'),
        new EnumNodeBuilder('Trapped', values: ['True', 'False', 'Unknown']),
    ]))]
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

    #[NormalizeGotenbergPayload]
    private function normalizeMetadata(): \Generator
    {
        yield 'metadata' => NormalizerFactory::json();
    }
}
