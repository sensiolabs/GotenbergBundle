<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Psr\Log\LoggerInterface;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\ChromiumTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\WebhookTrait;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Enumeration\Part;
use Sensiolabs\GotenbergBundle\Exception\PdfPartRenderingException;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class HtmlPdfBuilder extends AbstractBuilder
{
    use ChromiumTrait { ChromiumTrait::configure as configureChromium; }
    use WebhookTrait { WebhookTrait::configure as configureWebhook; }

    public function __construct(
        GotenbergClientInterface $client,
        AssetBaseDirFormatter $asset,
        array $defaultBodyData = [],
        LoggerInterface|null $logger = null,
        protected readonly Environment|null $twig = null,
        protected readonly UrlGeneratorInterface|null $urlGenerator = null,
    ) {
        parent::__construct($client, $asset, $defaultBodyData, $logger);
    }

    protected function configure(OptionsResolver $optionsResolver): void
    {
        parent::configure($optionsResolver);
        $this->configureChromium($optionsResolver);
        $this->configureWebhook($optionsResolver);
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

    public function contentFile(string $path): self
    {
        return $this->withFilePart(Part::Body, $path);
    }

    protected function getEndpoint(): string
    {
        return '/forms/chromium/convert/html';
    }

    protected function getTwig(): Environment
    {
        if (!$this->twig instanceof Environment) {
            throw new \LogicException(\sprintf('Twig is required to use "%s" method. Try to run "composer require symfony/twig-bundle".', __METHOD__));
        }

        return $this->twig;
    }

    protected function getUrlGenerator(): UrlGeneratorInterface
    {
        if (!$this->urlGenerator instanceof UrlGeneratorInterface) {
            throw new \LogicException(\sprintf('UrlGenerator is required to use "%s" method. Try to run "composer require symfony/router".', __METHOD__));
        }

        return $this->urlGenerator;
    }
}
