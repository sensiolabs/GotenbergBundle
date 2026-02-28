<?php

namespace Sensiolabs\GotenbergBundle\Builder\Screenshot;

use Sensiolabs\GotenbergBundle\Builder\AbstractBuilder;
use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\Attributes\WithBuilderConfiguration;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\ChromiumScreenshotTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\FilesTrait;
use Sensiolabs\GotenbergBundle\Builder\BuilderAssetInterface;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\Enumeration\Part;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Exception\PartRenderingException;

/**
 * You may have the possibility to convert Markdown files into screenshot.
 * You just need to wrap your markdown file into an HTML or Twig file.
 *
 * @see https://gotenberg.dev/docs/convert-with-chromium/screenshot-markdown
 *
 * @methodDoc files Add Markdown into a screenshot.
 *  Required to generate a screenshot from Markdown builder.
 *  You can pass several files with that method.
 *
 * @see https://gotenberg.dev/docs/convert-with-chromium/screenshot-markdown
 *
 * @example files('header.md','content.md','footer.md')
 */
#[WithBuilderConfiguration(type: 'screenshot', name: 'markdown')]
final class MarkdownScreenshotBuilder extends AbstractBuilder implements BuilderAssetInterface
{
    use ChromiumScreenshotTrait {
        content as private;
        contentFile as private;
    }
    use FilesTrait;

    public const ENDPOINT = '/forms/chromium/screenshot/markdown';

    private const AVAILABLE_EXTENSIONS = [
        'md',
    ];

    /**
     * The Twig file to convert into screenshot.
     *
     * Gotenberg expects an HTML template containing the directive {{ toHTML "filename.md" }}. To prevent any conflict,
     * you may want to use the verbatim tag to encapsulate the directive.
     *
     * @param string               $template #Template
     * @param array<string, mixed> $context
     *
     * @throws PartRenderingException if the template could not be rendered
     *
     * @see https://gotenberg.dev/docs/convert-with-chromium/screenshot-markdown
     * @see https://twig.symfony.com/doc/3.x/tags/verbatim.html
     *
     * @example wrapper('wrapper.html.twig', ['my_var' => 'value'])
     */
    public function wrapper(string $template, array $context = []): self
    {
        return $this->content($template, $context);
    }

    /**
     * The HTML file to wrap markdown file into screenshot.
     *
     * As assets files, by default the markdown files are fetch in the assets folder of your application.
     *
     * In the template, you must use the {{ toHTML "filename.md" }} special directive to reference the Markdown file.
     * The HTML template that receives your markdown file will look like this.
     *
     * @see https://gotenberg.dev/docs/convert-with-chromium/screenshot-markdown
     *
     * @example wrapperFile('../templates/wrapper.html')
     */
    public function wrapperFile(string $path): self
    {
        return $this->contentFile($path);
    }

    protected function getAllowedFilesExtensions(): array
    {
        return self::AVAILABLE_EXTENSIONS;
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
