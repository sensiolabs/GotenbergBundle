<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Client\PdfResponse;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Symfony\Component\HttpFoundation\File\File;

abstract class AbstractPdfBuilder implements PdfBuilderInterface
{
    /**
     * @var array<string, mixed>
     */
    protected array $formFields = [];

    public function __construct(
        protected readonly GotenbergClientInterface $gotenbergClient,
        protected readonly AssetBaseDirFormatter $asset,
    ) {
    }

    /**
     * Compiles the form values into a multipart form data array to send to the HTTP client.
     *
     * @return array<int, array<string, string>>
     *
     * @throws MissingRequiredFieldException
     */
    abstract public function getMultipartFormData(): array;

    /**
     * The Gotenberg API endpoint path.
     */
    abstract protected function getEndpoint(): string;

    /**
     * @param array<string, mixed> $configurations
     */
    abstract public function setConfigurations(array $configurations): self;

    public function generate(): PdfResponse
    {
        return $this->gotenbergClient->call($this->getEndpoint(), $this->getMultipartFormData());
    }

    /**
     * @param string[] $validExtensions
     */
    protected function assertFileExtension(string $path, array $validExtensions): void
    {
        $file = new File($this->asset->resolve($path));
        $extension = $file->getExtension();

        if (!\in_array($extension, $validExtensions, true)) {
            throw new \InvalidArgumentException(sprintf('The file extension "%s" is not available in Gotenberg.', $extension));
        }
    }
}
