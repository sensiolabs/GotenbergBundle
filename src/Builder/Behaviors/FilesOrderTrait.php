<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

use Psr\Log\LoggerInterface;
use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\Version\Version;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File;

trait FilesOrderTrait
{
    use FilesTrait {
        normalizeFiles as private normalizeFilesOriginal;
    }

    /**
     * @var 'name'|'call'
     */
    private string $filesSortMode = 'name';

    /**
     * Lets Gotenberg sort the files alphanumerically by their multipart filename.
     * This is the default behavior.
     */
    public function sortFilesByName(): self
    {
        $this->filesSortMode = 'name';

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
        $this->filesSortMode = 'call';

        return $this;
    }

    #[NormalizeGotenbergPayload]
    private function normalizeFiles(): \Generator
    {
        $mode = $this->filesSortMode;
        $delegate = NormalizerFactory::asset();

        yield 'files' => static function (string $key, array $assets, Version $version, LoggerInterface|null $logger) use ($mode, $delegate): \Generator {
            if ('call' !== $mode || [] === $assets) {
                yield from $delegate($key, $assets);

                return;
            }

            $index = 1;
            foreach ($assets as $entryKey => $info) {
                yield ['files' => new DataPart(
                    new File($info),
                    \sprintf('%06d-%s', $index++, basename($entryKey)),
                )];
            }
        };
    }
}
