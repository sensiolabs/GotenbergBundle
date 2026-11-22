<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\Util\ValidatorFactory;
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

    private bool $dedupeFiles = true;

    /**
     * Every file passed to files(), duplicates included, sent when dedupeFiles(false).
     *
     * @var list<\SplFileInfo>
     */
    private array $filesInCallOrder = [];

    /**
     * Lets Gotenberg sort the files alphanumerically by their multipart filename.
     * This is the default behavior.
     */
    public function sortFilesByName(): self
    {
        $this->filesNormalizer = self::normalizeFilesByName(...);

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
            foreach ($assets as $asset) {
                yield ['files' => new DataPart(new File($asset), \sprintf('%06d-%s', $index++, $asset->getFilename()))];
            }
        };

        return $this;
    }

    /**
     * Controls how files sharing the same resolved path or basename are
     * handled.
     *
     * When `true` (the default), duplicate file entries are dropped so that a
     * single occurrence reaches Gotenberg. This matches Gotenberg's flat
     * upload namespace, where files sharing a multipart filename overwrite
     * each other silently.
     *
     * When `false`, every file passed to `files()` is kept, even when several
     * entries share the same path or basename. Multipart filenames are
     * disambiguated with a zero-padded numeric suffix (e.g. `report.pdf`,
     * `report000001.pdf`) to bypass Gotenberg's silent overwrite of colliding
     * filenames.
     *
     * Caveat: Gotenberg sorts files alphanumerically by their multipart
     * filename. When the original basename already ends with a number
     * before its extension (e.g. `report_1.pdf`), the suffixed duplicates
     * (`report_1000001.pdf`) are sorted as a much larger integer and drift
     * to the end of the merge. Combine with `sortFilesByCall()` whenever the
     * resulting order matters, as the call-order prefix is alphanumerically
     * stable regardless of the original basename.
     */
    public function dedupeFiles(bool $dedupe = true): self
    {
        $this->dedupeFiles = $dedupe;

        return $this;
    }

    /**
     * Adds files (overrides any previous files).
     * The deduplication and ordering rules selected via `dedupeFiles()`,
     * `sortFilesByName()` or `sortFilesByCall()` are applied when the request
     * is built, so they can be configured before or after this call without
     * changing the result.
     *
     * @example files('document.pdf', '/absolute/path/document_2.pdf')
     */
    public function files(string|\Stringable ...$paths): self
    {
        $this->filesInCallOrder = [];
        foreach ($paths as $path) {
            $path = (string) $path;
            $info = new \SplFileInfo($this->getAssetBaseDirFormatter()->resolve($path));
            ValidatorFactory::filesExtension([$info], $this->getAllowedFilesExtensions());

            $files[$path] = $info;
            $this->filesInCallOrder[] = $info;
        }

        $this->getBodyBag()->set('files', $files ?? null);

        return $this;
    }

    #[NormalizeGotenbergPayload]
    private function normalizeFiles(): \Generator
    {
        $normalizer = $this->filesNormalizer ?? self::normalizeFilesByName(...);
        $files = $this->dedupeFiles ? null : self::disambiguateFilenames($this->filesInCallOrder);

        yield 'files' => static fn (string $key, array $assets): iterable => $normalizer($key, $files ?? $assets);
    }

    /**
     * Sends each file under the basename of its key.
     *
     * @param array<string, \SplFileInfo> $assets
     *
     * @return \Generator<int, array{files: DataPart}>
     */
    private static function normalizeFilesByName(string $key, array $assets): \Generator
    {
        foreach ($assets as $name => $asset) {
            yield ['files' => new DataPart(new File($asset), basename((string) $name))];
        }
    }

    /**
     * Keys each file by its basename, suffixing repeats before the extension (e.g. `report000001.pdf`).
     *
     * @param list<\SplFileInfo> $assets
     *
     * @return array<string, \SplFileInfo>
     */
    private static function disambiguateFilenames(array $assets): array
    {
        $files = [];
        foreach ($assets as $info) {
            $filename = $info->getFilename();
            $extension = $info->getExtension();
            $stem = $info->getBasename('' !== $extension ? '.'.$extension : '');

            for ($count = 1; isset($files[$filename]); ++$count) {
                $filename = $stem.\sprintf('%06d', $count).('' !== $extension ? '.'.$extension : '');
            }

            $files[$filename] = $info;
        }

        return $files;
    }
}
