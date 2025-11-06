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
use Sensiolabs\GotenbergBundle\Exception\PartRenderingException;

/**
 * You may have the possibility to convert Markdown files into PDF.
 * You just need to wrap your markdown file into an HTML or Twig file.
 *
 * @see https://gotenberg.dev/docs/routes#markdown-files-into-pdf-route
 */
#[WithBuilderConfiguration(type: 'pdf', name: 'markdown')]
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
     * Gotenberg expects an HTML template containing the directive {{ toHTML "filename.md" }}. To prevent any conflict,
     * you may want to use the verbatim tag to encapsulate the directive.
     *
     * @param string               $template #Template
     * @param array<string, mixed> $context
     *
     * @throws PartRenderingException if the template could not be rendered
     *
     * @see https://gotenberg.dev/docs/routes#markdown-files-into-pdf-route
     * @see https://twig.symfony.com/doc/3.x/tags/verbatim.html
     *
     * @example wrapper('wrapper.html.twig', ['my_var' => 'value'])
     */
    public function wrapper(string $template, array $context = []): self
    {
        return $this->content($template, $context);
    }

    /**
     * The HTML file that wraps the markdown content.
     *
     * As assets files, by default the markdown files are fetch in the assets folder of your application.
     *
     * In the template, you must use the {{ toHTML "filename.md" }} special directive to reference the Markdown file.
     * The HTML template that receives your markdown file will look like this.
     *
     * @see https://gotenberg.dev/docs/routes#markdown-files-into-pdf-route
     *
     * @example wrapperFile('../templates/wrapper.html')
     */
    public function wrapperFile(string $path): self
    {
        return $this->contentFile($path);
    }

    /**
     * Add Markdown into a PDF.
     *
     * Required to generate a PDF from Markdown builder. You can pass several files with that method.
     *
     * As assets files, by default the markdown files are fetch in the assets folder of your application.
     *
     * @see https://gotenberg.dev/docs/routes#markdown-files-into-pdf-route
     *
     * @example files('header.md','content.md','footer.md')
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
