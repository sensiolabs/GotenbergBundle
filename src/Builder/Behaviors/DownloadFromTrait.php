<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\Util\ValidatorFactory;
use Sensiolabs\GotenbergBundle\Client\BodyBag;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @see https://gotenberg.dev/docs/routes#download-from
 */
trait DownloadFromTrait
{
    abstract protected function getBodyBag(): BodyBag;

    protected function configure(OptionsResolver $bodyOptionsResolver, OptionsResolver $headersOptionsResolver): void
    {
        $bodyOptionsResolver
            ->define('downloadFrom')
            ->info('URLs to download files from (JSON format).')
            ->allowedTypes('string[]')
            ->allowedValues(ValidatorFactory::download())
        ;
    }

    /**
     * Sets download from to download each entry (file) in parallel (default None).
     * (URLs MUST return a Content-Disposition header with a filename parameter.).
     *
     * @param list<array{url: string, extraHttpHeaders?: array<string, string>}> $downloadFrom
     */
    public function downloadFrom(array $downloadFrom): static
    {
        if ([] === $downloadFrom) {
            $this->getBodyBag()->unset('downloadFrom');

            return $this;
        }

        $value = $this->getBodyBag()->get('downloadFrom', []);

        foreach ($downloadFrom as $file) {
            $value[$file['url']] = $file;
        }

        $this->getBodyBag()->set('downloadFrom', $value);

        return $this;
    }
}
