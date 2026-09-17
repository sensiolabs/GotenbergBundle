<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\AssetBaseDirFormatterAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\Builder\Util\ValidatorFactory;

trait FilesTrait
{
    use AssetBaseDirFormatterAwareTrait;

    abstract protected function getBodyBag(): BodyBag;

    /**
     * @return array<string>
     */
    abstract protected function getAllowedFilesExtensions(): array;

    /**
     * Adds files (overrides any previous files).
     *
     * @example files('document.pdf', '/absolute/path/document_2.pdf')
     *
     * Files are deduplicated by path: passing the same path twice to `files()`
     * results in a single file being sent. To merge the same document multiple
     * times, provide distinct paths (e.g. copies with different names).
     */
    public function files(string|\Stringable ...$paths): self
    {
        foreach ($paths as $path) {
            $path = (string) $path;
            $info = new \SplFileInfo($this->getAssetBaseDirFormatter()->resolve($path));
            ValidatorFactory::filesExtension([$info], $this->getAllowedFilesExtensions());

            $files[$path] = $info;
        }

        $this->getBodyBag()->set('files', $files ?? null);

        return $this;
    }

    #[NormalizeGotenbergPayload]
    private function normalizeFiles(): \Generator
    {
        yield 'files' => NormalizerFactory::asset();
    }
}
