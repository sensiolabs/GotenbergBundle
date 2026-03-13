<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\AssetBaseDirFormatterAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\LoggerAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;

trait EmbedTrait
{
    use AssetBaseDirFormatterAwareTrait;
    use LoggerAwareTrait;

    abstract protected function getBodyBag(): BodyBag;

    /**
     * Add files to embed.
     *
     * As assets files, by default the files to embed are fetch in the assets folder
     * of your application. For more information about path resolution go to
     * assets documentation.
     *
     * @see https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#attachments-pdf-engines
     *
     * @example embedFiles('document.xml','document_2.json')
     */
    public function embedFiles(string|\Stringable ...$paths): self
    {
        $this->logWarningIfVersionIs('<', '8.25', 'The embeds option is not available.');

        foreach ($paths as $path) {
            $path = (string) $path;

            $info = new \SplFileInfo($this->getAssetBaseDirFormatter()->resolve($path));
            $files[$path] = $info;
        }

        $this->getBodyBag()->set('embeds', $files ?? null);

        return $this;
    }

    #[NormalizeGotenbergPayload]
    private function normalizeEmbed(): \Generator
    {
        yield 'embeds' => NormalizerFactory::embed();
    }
}
