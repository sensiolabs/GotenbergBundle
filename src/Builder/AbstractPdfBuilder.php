<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Client\PdfResponse;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\String\UnicodeString;

abstract class AbstractPdfBuilder implements PdfBuilderInterface
{
    /**
     * @var array<string, mixed>
     */
    protected array $formFields = [];

    public function __construct(
        protected readonly GotenbergClientInterface $gotenbergClient,
        protected readonly string $projectDir,
    ) {
    }

    /**
     * The Gotenberg API endpoint path.
     */
    abstract public function getEndpoint(): string;

    /**
     * Compiles the form values into a multipart form data array to send to the HTTP client.
     *
     * @return array<int, array<string, string>>
     */
    abstract public function getMultipartFormData(): array;

    public function generate(): PdfResponse
    {
        return $this->gotenbergClient->post($this->getEndpoint(), $this->getMultipartFormData());
    }

    /**
     * To set configurations by an array of configurations.
     *
     * @param array<string, mixed> $configurations
     */
    public function setConfigurations(array $configurations): static
    {
        foreach ($configurations as $property => $value) {
            $method = (new UnicodeString($property))->camel()->toString();
            if (!method_exists($this, $method)) {
                throw new \InvalidArgumentException(sprintf('Invalid option "%s": the method "%s" does not exist in class "%s".', $property, $method, static::class));
            }

            $this->{$method}($value);
        }

        return $this;
    }

    /**
     * @param string[] $validExtensions
     */
    protected function assertFileExtension(string $path, array $validExtensions): void
    {
        $path = str_starts_with('/', $path) ? $path : $this->projectDir.'/'.$path;
        $file = new File($path);
        $extension = $file->getExtension();

        if (!\in_array($extension, $validExtensions, true)) {
            throw new \InvalidArgumentException(sprintf('The file extension "%s" is not available in Gotenberg.', $extension));
        }
    }

    protected function resolveFilePath(string $path): string
    {
        return str_starts_with('/', $path) ? $path : $this->projectDir.'/'.$path;
    }
}
