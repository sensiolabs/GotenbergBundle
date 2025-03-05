<?php

namespace Sensiolabs\GotenbergBundle\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\AbstractBuilder;
use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\Attributes\SemanticNode;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\ChromiumPdfTrait;
use Sensiolabs\GotenbergBundle\Builder\BuilderAssetInterface;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\Builder\Util\ValidatorFactory;
use Sensiolabs\GotenbergBundle\Enumeration\Part;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;

/**
 * @see https://gotenberg.dev/docs/routes#merge-pdfs-route
 */
#[SemanticNode('markdown')]
final class MarkdownPdfBuilder extends AbstractBuilder implements BuilderAssetInterface
{
    use ChromiumPdfTrait {
        content as wrapper;
        contentFile as wrapperFile;
    }

    /**
     * Add Markdown into a PDF.
     *
     * @see https://gotenberg.dev/docs/routes#markdown-files-into-pdf-route
     */
    public function files(string ...$paths): self
    {
        foreach ($paths as $path) {
            $info = new \SplFileInfo($this->getAssetBaseDirFormatter()->resolve($path));
            ValidatorFactory::filesExtension([$info], ['md']);

            $files[$path] = $info;
        }

        $this->getBodyBag()->set('files', $files ?? null);

        return $this;
    }

    /**
     * @param array<string, mixed> $context
     */
    public function content(string $template, array $context = []): void
    {
        throw new \BadMethodCallException('Use wrapper() instead of content().');
    }

    public function contentFile(string $path): void
    {
        throw new \BadMethodCallException('Use wrapperFile() instead of contentFile().');
    }

    protected function getEndpoint(): string
    {
        return '/forms/chromium/convert/markdown';
    }

    protected function validatePayloadBody(): void
    {
        if ($this->getBodyBag()->get(Part::Body->value) === null) {
            throw new MissingRequiredFieldException('HTML template is required');
        }

        if ($this->getBodyBag()->get('files') === null && $this->getBodyBag()->get('downloadFrom') === null) {
            throw new MissingRequiredFieldException('At least one markdown file is required.');
        }
    }

    #[NormalizeGotenbergPayload]
    private function normalizeFiles(): \Generator
    {
        yield 'files' => NormalizerFactory::asset();
    }

    public static function type(): string
    {
        return 'pdf';
    }
}
