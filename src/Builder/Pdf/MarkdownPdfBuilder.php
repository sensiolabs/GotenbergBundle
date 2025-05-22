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
use Sensiolabs\GotenbergBundle\Exception\PartRenderingException;

/**
 * @see https://gotenberg.dev/docs/routes#markdown-files-into-pdf-route
 */
#[SemanticNode(type: 'pdf', name: 'markdown')]
final class MarkdownPdfBuilder extends AbstractBuilder implements BuilderAssetInterface
{
    use ChromiumPdfTrait {
        content as private;
        contentFile as private;
    }

    public const ENDPOINT = '/forms/chromium/convert/markdown';

    /**
     * The template that wraps the markdown content.
     *
     * @param string               $template #Template
     * @param array<string, mixed> $context
     *
     * @throws PartRenderingException if the template could not be rendered
     */
    public function wrapper(string $template, array $context = []): self
    {
        return $this->content($template, $context);
    }

    /**
     * The HTML file that wraps the markdown content.
     */
    public function wrapperFile(string $path): self
    {
        return $this->contentFile($path);
    }

    /**
     * Add Markdown into a PDF.
     *
     * @see https://gotenberg.dev/docs/routes#markdown-files-into-pdf-route
     */
    public function files(string|\Stringable ...$paths): self
    {
        foreach ($paths as $path) {
            $path = (string) $path;
            $info = new \SplFileInfo($this->getAssetBaseDirFormatter()->resolve($path));
            ValidatorFactory::filesExtension([$info], ['md']);

            $files[$path] = $info;
        }

        $this->getBodyBag()->set('files', $files ?? null);

        return $this;
    }

    protected function getEndpoint(): string
    {
        return self::ENDPOINT;
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
}
