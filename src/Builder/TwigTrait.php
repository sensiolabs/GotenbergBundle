<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Enum\PdfPart;
use Sensiolabs\GotenbergBundle\Exception\PdfPartRenderingException;
use Symfony\Component\Mime\Part\DataPart;
use Twig\Environment;

trait TwigTrait
{
    public function renderPart(PdfPart $pdfPart, string $template, array $context = []): self
    {
        if (!$this->twig instanceof Environment) {
            throw new \LogicException(sprintf('Twig is required to use "%s" method. Try to run "composer require symfony/twig-bundle".', __METHOD__));
        }

        try {
            $html = $this->twig->render($template, $context);
        } catch (\Throwable $error) {
            throw new PdfPartRenderingException(sprintf('Could not render template "%s" into PDF part "%s".', $template, $pdfPart->value), previous: $error);
        }

        $this->formFields[$pdfPart->value] = new DataPart($html, $pdfPart->value, 'text/html');

        return $this;
    }
}
