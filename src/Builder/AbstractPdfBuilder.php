<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Client\PdfResponse;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\HeaderUtils;

abstract class AbstractPdfBuilder implements PdfBuilderInterface
{
    /**
     * @var array<string, mixed>
     */
    protected array $formFields = [];

    private string|null $fileName;

    public function __construct(
        protected readonly GotenbergClientInterface $gotenbergClient,
        protected readonly AssetBaseDirFormatter $asset,
    ) {
    }

    /**
     * Compiles the form values into a multipart form data array to send to the HTTP client.
     *
     * @return array<int, array<string, string>>
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

    public function withFileName(string $fileName): static
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function generate(): PdfResponse
    {
        $pdfResponse = $this->gotenbergClient->call($this->getEndpoint(), $this->getMultipartFormData());

        if (null !== $this->fileName) {
            $disposition = HeaderUtils::makeDisposition(
                HeaderUtils::DISPOSITION_INLINE, # TODO : make dynamic
                $this->fileName
            );

            $pdfResponse
                ->headers->set('Content-Disposition', $disposition)
            ;
        }

        return $pdfResponse;
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
