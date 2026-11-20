<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File;

trait FilesOrderTrait
{
    use FilesTrait;

    /**
     * Normalizer of the "files" payload, sorted by name when null.
     *
     * @var (\Closure(string, array<string, \SplFileInfo>): iterable<array{files: DataPart}>)|null
     */
    private \Closure|null $filesNormalizer = null;

    /**
     * Lets Gotenberg sort the files alphanumerically by their multipart filename.
     * This is the default behavior.
     */
    public function sortFilesByName(): self
    {
        $this->filesNormalizer = NormalizerFactory::asset();

        return $this;
    }

    /**
     * Preserves the order in which files were added to the builder.
     * Each file's multipart filename is prefixed with a zero-padded counter
     * (e.g. `000001-document.pdf`) so that Gotenberg's alphanumeric sort
     * yields the original call order. The file on disk is not renamed.
     */
    public function sortFilesByCall(): self
    {
        $this->filesNormalizer = static function (string $key, array $assets): \Generator {
            $index = 1;
            foreach ($assets as $path => $asset) {
                yield ['files' => new DataPart(new File($asset), \sprintf('%06d-%s', $index++, basename($path)))];
            }
        };

        return $this;
    }

    #[NormalizeGotenbergPayload]
    private function normalizeFiles(): \Generator
    {
        yield 'files' => $this->filesNormalizer ?? NormalizerFactory::asset();
    }
}
