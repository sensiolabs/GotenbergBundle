<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Builder\Behaviors\MetadataTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\PdfFormatTrait;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File as DataPartFile;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @see https://gotenberg.dev/docs/routes#merge-pdfs-route
 */
class MergePdfBuilder extends AbstractBuilder
{
    use MetadataTrait {
        MetadataTrait::configure as configureMetadata;
    }
    use PdfFormatTrait {
        PdfFormatTrait::configure as configurePdfFormat;
    }

    protected function getEndpoint(): string
    {
        return '/forms/pdfengines/merge';
    }

    protected function configure(OptionsResolver $bodyOptionsResolver, OptionsResolver $headersOptionsResolver): void
    {
        parent::configure($bodyOptionsResolver, $headersOptionsResolver);
        $this->configureMetadata($bodyOptionsResolver, $headersOptionsResolver);
        $this->configurePdfFormat($bodyOptionsResolver, $headersOptionsResolver);

        $bodyOptionsResolver
            ->define('files')
            ->info('Add PDF files to merge')
            ->normalize(function (Options $options, array $values): array {
                return array_map(
                    fn (string $value): DataPart => new DataPart(new DataPartFile($this->asset->resolve($value))),
                    $values,
                );
            })
            ->allowedValues(function (array $values): bool {
                /** @var list<string> $values */
                foreach ($values as $value) {
                    $ext = (new File($this->asset->resolve($value)))->getExtension();
                    if ('pdf' !== $ext) {
                        throw new InvalidOptionsException(\sprintf('The option "files" expects files with a "pdf" extension, but "%s" has a "%s" extension.', $value, $ext));
                    }
                }

                return true;
            })
        ;
    }

    /**
     * Add PDF files to merge.
     *
     * @see https://gotenberg.dev/docs/routes#merge-pdfs-route
     */
    public function files(string ...$paths): self
    {
        $this->getBodyBag()->set('files', $paths);

        return $this;
    }
}
