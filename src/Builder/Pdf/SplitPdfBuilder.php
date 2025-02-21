<?php

namespace Sensiolabs\GotenbergBundle\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Enumeration\SplitMode;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File as DataPartFile;

/**
 * Split `n` pdf files.
 */
final class SplitPdfBuilder extends AbstractPdfBuilder
{
    private const ENDPOINT = '/forms/pdfengines/split';

    /**
     * To set configurations by an array of configurations.
     *
     * @param array<string, mixed> $configurations
     */
    public function setConfigurations(array $configurations): static
    {
        foreach ($configurations as $property => $value) {
            $this->addConfiguration($property, $value);
        }

        return $this;
    }

    /**
     * Either intervals or pages. (default None).
     *
     * @see https://gotenberg.dev/docs/routes#split-pdfs-route
     */
    public function splitMode(SplitMode|null $splitMode = null): self
    {
        if (null === $splitMode) {
            unset($this->formFields['splitMode']);

            return $this;
        }

        $this->formFields['splitMode'] = $splitMode;

        return $this;
    }

    /**
     * Either the intervals or the page ranges to extract, depending on the selected mode. (default None).
     *
     * @see https://gotenberg.dev/docs/routes#split-pdfs-route
     */
    public function splitSpan(string $splitSpan): self
    {
        $this->formFields['splitSpan'] = $splitSpan;

        return $this;
    }

    /**
     * Specify whether to put extracted pages into a single file or as many files as there are page ranges. Only works with pages mode. (default false).
     *
     * @see https://gotenberg.dev/docs/routes#split-pdfs-route
     */
    public function splitUnify(bool $bool = true): self
    {
        $this->formFields['splitUnify'] = $bool;

        return $this;
    }

    public function files(string|\Stringable ...$paths): self
    {
        $this->formFields['files'] = [];

        foreach ($paths as $path) {
            $path = (string) $path;

            $this->assertFileExtension($path, ['pdf']);

            $dataPart = new DataPart(new DataPartFile($this->asset->resolve($path)));

            $this->formFields['files'][$path] = $dataPart;
        }

        return $this;
    }

    public function getMultipartFormData(): array
    {
        if (!\array_key_exists('splitMode', $this->formFields) || !\array_key_exists('splitSpan', $this->formFields)) {
            throw new MissingRequiredFieldException('"splitMode" and "splitSpan" must be provided.');
        }

        if ([] === ($this->formFields['files'] ?? [])) {
            throw new MissingRequiredFieldException('At least one PDF file is required');
        }

        return parent::getMultipartFormData();
    }

    protected function getEndpoint(): string
    {
        return self::ENDPOINT;
    }

    private function addConfiguration(string $configurationName, mixed $value): void
    {
        match ($configurationName) {
            'split_mode' => $this->splitMode(SplitMode::from($value)),
            'split_span' => $this->splitSpan($value),
            'split_unify' => $this->splitUnify($value),
            default => throw new InvalidBuilderConfiguration(\sprintf('Invalid option "%s": no method does not exist in class "%s" to configured it.', $configurationName, static::class)),
        };
    }
}
