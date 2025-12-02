<?php

namespace Sensiolabs\GotenbergBundle\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\AbstractBuilder;
use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\Attributes\WithBuilderConfiguration;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\ChromiumPdfTrait;
use Sensiolabs\GotenbergBundle\Builder\BuilderAssetInterface;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\Builder\Util\ValidatorFactory;
use Sensiolabs\GotenbergBundle\Enumeration\Part;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;

/**
 * You may have the possibility to convert HTML or Twig files into PDF.
 *
 * @see https://gotenberg.dev/docs/routes#html-file-into-pdf-route
 */
#[WithBuilderConfiguration(type: 'pdf', name: 'html')]
final class HtmlPdfBuilder extends AbstractBuilder implements BuilderAssetInterface
{
    use ChromiumPdfTrait;

    public const ENDPOINT = '/forms/chromium/convert/html';

    /**
     * Add file to embed.
     *
     * As assets files, by default the files to embed are fetch in the assets folder
     * of your application. For more information about path resolution go to
     * assets documentation.
     *
     * @see https://gotenberg.dev/docs/routes#embeds-chromium
     *
     * @example embeds('document.xml','document_2.json')
     */
    public function embeds(string|\Stringable ...$paths): self
    {
        $this->introducedIn('8.25');

        foreach ($paths as $path) {
            $path = (string) $path;

            $info = new \SplFileInfo($this->getAssetBaseDirFormatter()->resolve($path));
            ValidatorFactory::filesExtension([$info], ['xml', 'json']);

            $files[$path] = $info;
        }

        $this->getBodyBag()->set('embeds', $files ?? null);

        return $this;
    }

    protected function getEndpoint(): string
    {
        return self::ENDPOINT;
    }

    protected function validatePayloadBody(): void
    {
        if ($this->getBodyBag()->get(Part::Body->value) === null && $this->getBodyBag()->get('downloadFrom') === null) {
            throw new MissingRequiredFieldException('Content is required');
        }
    }

    #[NormalizeGotenbergPayload]
    private function normalizeFiles(): \Generator
    {
        yield 'embeds' => NormalizerFactory::embed();
    }
}
