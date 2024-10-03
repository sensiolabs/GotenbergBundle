<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Builder\Behaviors\ChromiumTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\WebhookTrait;
use Sensiolabs\GotenbergBundle\Enumeration\Part;
use Sensiolabs\GotenbergBundle\Exception\PdfPartRenderingException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HtmlPdfBuilder extends AbstractBuilder implements BuilderAssetInterface
{
    use ChromiumTrait { ChromiumTrait::configure as configureChromium; }
    use WebhookTrait { WebhookTrait::configure as configureWebhook; }

    protected function getEndpoint(): string
    {
        return '/forms/chromium/convert/html';
    }

    protected function configure(OptionsResolver $bodyOptionsResolver, OptionsResolver $headersOptionsResolver): void
    {
        parent::configure($bodyOptionsResolver, $headersOptionsResolver);
        $this->configureChromium($bodyOptionsResolver, $headersOptionsResolver);
        $this->configureWebhook($bodyOptionsResolver, $headersOptionsResolver);
    }

    /**
     * @param string               $template #Template
     * @param array<string, mixed> $context
     *
     * @throws PdfPartRenderingException if the template could not be rendered
     */
    public function content(string $template, array $context = []): self
    {
        return $this->withRenderedPart(Part::Body, $template, $context);
    }

    /**
     * The HTML file to convert into PDF.
     */
    public function contentFile(string $path): self
    {
        return $this->withFilePart(Part::Body, $path);
    }
}
