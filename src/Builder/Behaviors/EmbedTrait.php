<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\AssetBaseDirFormatterAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\LoggerAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\Builder\ValueObject\EmbeddedFile;
use Symfony\Component\Mime\MimeTypes;

trait EmbedTrait
{
    use AssetBaseDirFormatterAwareTrait;
    use LoggerAwareTrait;

    abstract protected function getBodyBag(): BodyBag;

    /**
     * Set files to embed.
     *
     * As assets files, by default the files to embed are fetch in the assets folder
     * of your application. For more information about path resolution go to
     * assets documentation.
     *
     * @see https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#attachments-pdf-engines
     *
     * @example embedFiles('document.xml','document_2.json')
     * @example embedFiles(new EmbeddedFile('factur-x.xml', 'Data'))
     */
    public function embedFiles(string|\Stringable|EmbeddedFile ...$paths): self
    {
        $this->logWarningIfVersionIs('<', '8.25', 'The embeds option is not available.');

        $files = [];
        $metadata = [];

        foreach ($paths as $item) {
            // Add the file
            if ($item instanceof \SplFileInfo) {
                $filename = $item->getFilename();
                $file = $item;
            } else {
                $filename = (string) $item;
                $file = new \SplFileInfo($this->getAssetBaseDirFormatter()->resolve((string) $item));
            }
            $files[$filename] = $file;

            // Add the file metadata
            if ($item instanceof EmbeddedFile) {
                $metadata[$filename] = [
                    'mimeType' => $item->getMimeType() ?? (new MimeTypes())->guessMimeType($item->getRealPath()),
                ];
                if (null !== $item->getRelationship()) {
                    $metadata[$filename]['relationship'] = $item->getRelationship();
                }
            }
        }

        $this->getBodyBag()->set('embeds', [] !== $files ? $files : null);
        if ([] !== $metadata) {
            $this->logWarningIfVersionIs('<', '8.31', 'The embedsMetadata option is not available.');
            $this->getBodyBag()->set('embedsMetadata', $metadata);
        }

        return $this;
    }

    #[NormalizeGotenbergPayload]
    private function normalizeEmbed(): \Generator
    {
        yield 'embeds' => NormalizerFactory::embed();
        yield 'embedsMetadata' => NormalizerFactory::json();
    }
}
