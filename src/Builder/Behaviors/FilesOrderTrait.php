<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\Builder\Util\ValidatorFactory;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File;

trait FilesOrderTrait
{
    use FilesTrait {
        files as private filesOriginal;
        normalizeFiles as private normalizeFilesOriginal;
    }

    /**
     * @var 'name'|'call'
     */
    private string $filesSortMode = 'name';

    private bool $dedupeFiles = true;

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
     *
     * @example files('document.pdf', '/absolute/path/document_2.pdf')
     *
     * The deduplication and ordering rules selected via `dedupeFiles()`,
     * `sortFilesByName()` or `sortFilesByCall()` are applied when the request
     * is built, so they can be configured before or after this call without
     * changing the result.
     */
    public function files(string|\Stringable ...$paths): self
    {
        /** @var list<array{0: string, 1: \SplFileInfo}> $files */
        $files = [];
        foreach ($paths as $path) {
            $path = (string) $path;
            $info = new \SplFileInfo($this->getAssetBaseDirFormatter()->resolve($path));
            ValidatorFactory::filesExtension([$info], $this->getAllowedFilesExtensions());

            $files[] = [$path, $info];
        }

        $this->getBodyBag()->set('files', [] !== $files ? $files : null);

        return $this;
    }

    #[NormalizeGotenbergPayload]
    private function normalizeFiles(): \Generator
    {
        $sortMode = $this->filesSortMode;
        $dedupe = $this->dedupeFiles;

        yield 'files' => static function (string $key, array $assets) use ($sortMode, $dedupe): \Generator {
            /** @var list<array{0: string, 1: \SplFileInfo}> $assets */
            if ($dedupe) {
                // Collapse duplicate paths the same way `FilesTrait` does, so
                // the request sent to Gotenberg stays backward-compatible.
                $deduped = [];
                foreach ($assets as [$path, $info]) {
                    $deduped[$path] = $info;
                }

                if ('call' === $sortMode) {
                    $index = 1;
                    foreach ($deduped as $path => $info) {
                        $filename = \sprintf('%06d-%s', $index++, basename((string) $path));
                        yield ['files' => new DataPart(new File($info), $filename)];
                    }

                    return;
                }

                // Delegate to the same normalizer FilesTrait uses for 'files'.
                $assetNormalizer = NormalizerFactory::asset();
                yield from $assetNormalizer($key, $deduped);

                return;
            }

            // Keep every tuple in call order.
            if ('call' === $sortMode) {
                $index = 1;
                foreach ($assets as [$path, $info]) {
                    $filename = \sprintf('%06d-%s', $index++, basename($path));
                    yield ['files' => new DataPart(new File($info), $filename)];
                }

                return;
            }

            foreach (self::disambiguateFilenames($assets) as [$filename, $info]) {
                yield ['files' => new DataPart(new File($info), $filename)];
            }
        };
    }

    /**
     * Yields [filename, SplFileInfo] pairs, suffixing repeated basenames
     * with a zero-padded counter inserted before the extension
     * (e.g. `report.pdf`, `report000001.pdf`, `report000002.pdf`) so that
     * every file keeps a distinct multipart filename on the wire. No `-`
     * separator is used because it would shift sort order against the
     * original basename in Gotenberg's alphanumeric merge.
     *
     * @param list<array{0: string, 1: \SplFileInfo}> $assets
     *
     * @return list<array{0: string, 1: \SplFileInfo}>
     */
    private static function disambiguateFilenames(array $assets): array
    {
        $seen = [];
        $result = [];
        foreach ($assets as [$path, $info]) {
            $basename = basename($path);
            $count = $seen[$basename] ?? 0;
            $seen[$basename] = $count + 1;

            if (0 === $count) {
                $result[] = [$basename, $info];

                continue;
            }

            $extension = $info->getExtension();
            $stem = $info->getBasename('' !== $extension ? '.'.$extension : '');
            $suffix = '' !== $extension ? \sprintf('%06d.%s', $count, $extension) : \sprintf('%06d', $count);

            $result[] = [$stem.$suffix, $info];
        }

        return $result;
    }
}
