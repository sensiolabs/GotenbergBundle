<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\Attributes\ExposeSemantic;
use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\Enumeration\NodeType;

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
    #[ExposeSemantic('metadata', NodeType::Array, ['has_parent_node' => true, 'children' => [
        ['name' => 'Author'],
        ['name' => 'Copyright'],
        ['name' => 'CreationDate'],
        ['name' => 'Creator'],
        ['name' => 'Keywords'],
        ['name' => 'Marked', 'node_type' => NodeType::Boolean],
        ['name' => 'ModDate'],
        ['name' => 'PDFVersion'],
        ['name' => 'Producer'],
        ['name' => 'Subject'],
        ['name' => 'Title'],
        ['name' => 'Trapped', 'node_type' => NodeType::Enum, 'options' => ['values' => ['True', 'False', 'Unknown']]],
    ]])]
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
