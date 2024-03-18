<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Client\PdfResponse;
use Sensiolabs\GotenbergBundle\Exception\ExtraHttpHeadersJsonEncodingException;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Exception\NotFoundPropertyInMultipartFormDataException;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Mime\Part\DataPart;

abstract class AbstractPdfBuilder implements PdfBuilderInterface
{
    /**
     * @var list<array<string, mixed>>
     */
    protected array $multipartFormData = [];

    public function __construct(
        protected readonly GotenbergClientInterface $gotenbergClient,
        protected readonly AssetBaseDirFormatter $asset,
    ) {
    }

    /**
     * @return array<int, array<string, mixed>>
     *
     * @throws MissingRequiredFieldException
     */
    public function getMultipartFormData(): array
    {
        return $this->formatMultipartFormData($this->multipartFormData);
    }

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

    protected function addPropertyToMultipartFormDataWithExistenceCheck(string $property, mixed $value): static
    {
        $hasProperty = $this->multipartFormDataPropertyExistenceChecker($property);

        if (!$hasProperty) {
            $this->multipartFormData[] = [$property => $value];

            return $this;
        }

        $this->multipartFormData[$this->getIndexForExistingPropertyToOverride($property)] = [$property => $value];

        return $this;
    }

    protected function getIndexForExistingPropertyToOverride(string $property): int
    {
        foreach ($this->multipartFormData as $index => $data) {
            if (\array_key_exists($property, $data)) {
                return $index;
            }
        }

        throw new NotFoundPropertyInMultipartFormDataException(sprintf('Property %s not found in multipartFormData.', $property));
    }

    protected function multipartFormDataPropertyExistenceChecker(string $property): bool
    {
        foreach ($this->multipartFormData as $data) {
            if (\array_key_exists($property, $data)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array<int, array<string, mixed>> $multipartFormData
     *
     * @return array<int, array<string, mixed>>
     */
    private function formatMultipartFormData(array $multipartFormData): array
    {
        foreach ($multipartFormData as $index => $data) {
            foreach ($data as $key => $value) {
                if (!$value instanceof DataPart) {
                    if (\is_array($value)) {
                        try {
                            $extraHttpHeaders = json_encode($value, \JSON_THROW_ON_ERROR);
                        } catch (\JsonException $exception) {
                            throw new ExtraHttpHeadersJsonEncodingException('Could not encode extra HTTP headers into JSON', previous: $exception);
                        }
                        $multipartFormData[$index] = [$key => $extraHttpHeaders];
                    } else {
                        $multipartFormData[$index] = [$key => (string) $value];
                    }
                }
            }
        }

        return $multipartFormData;
    }
}
