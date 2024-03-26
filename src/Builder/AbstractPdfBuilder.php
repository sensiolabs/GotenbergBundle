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

    private ?string $fileName = null;

    private string $headerDisposition = HeaderUtils::DISPOSITION_INLINE;

    public function __construct(
        protected readonly GotenbergClientInterface $gotenbergClient,
        protected readonly AssetBaseDirFormatter $asset,
    ) {
    }

    /**
     * Compiles the form values into a multipart form data array to send to the HTTP client.
     *
     * @return list<array<string, string>>
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

    /**
     * @param HeaderUtils::DISPOSITION_* $headerDisposition
     */
    public function fileName(string $fileName, string $headerDisposition = HeaderUtils::DISPOSITION_INLINE): static
    {
        $this->fileName = $fileName;
        $this->headerDisposition = $headerDisposition;

        return $this;
    }

    public function generate(): PdfResponse
    {
        $pdfResponse = $this->gotenbergClient->call($this->getEndpoint(), $this->getMultipartFormData());

        if (null !== $this->fileName) {
            $disposition = HeaderUtils::makeDisposition(
                $this->headerDisposition,
                $this->fileName,
            );

            $pdfResponse
                ->headers->set('Content-Disposition', $disposition)
            ;
        }

        return $pdfResponse;
    }

    /**
     * @param non-empty-list<string> $validExtensions
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
