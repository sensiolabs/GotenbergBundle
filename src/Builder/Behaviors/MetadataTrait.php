<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\Attributes\WithConfigurationNode;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\NodeBuilder\BooleanNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\EnumNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\MetadataNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\ScalarNodeBuilder;

trait MetadataTrait
{
    abstract protected function getBodyBag(): BodyBag;

    /**
     * Resets the metadata.
     *
     * @see https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#metadata-pdf-engines
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
     *
     * @example metadata(['Author' => 'SensioLabs', 'Subject' => 'Gotenberg'])
     */
    #[WithConfigurationNode(new MetadataNodeBuilder('metadata', children: [
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
        $this->logWarningIfVersionIs('<', '8.3', 'The metadata option is not available.');

        $this->getBodyBag()->set('metadata', $metadata);

        return $this;
    }

    /**
     * If you want to add metadata from the ones already loaded in the configuration.
     *
     * @example addMetadata('key', 'value')
     */
    public function addMetadata(string $key, string $value): static
    {
        $this->logWarningIfVersionIs('<', '8.3', 'The metadata option is not available.');

        $this->getBodyBag()->set('metadata', [$key => $value] + $this->getBodyBag()->get('metadata', []));

        return $this;
    }

    #[NormalizeGotenbergPayload]
    private function normalizeMetadata(): \Generator
    {
        yield 'metadata' => NormalizerFactory::json();
    }
}
