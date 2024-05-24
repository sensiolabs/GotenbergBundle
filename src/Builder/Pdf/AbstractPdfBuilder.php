<?php

namespace Sensiolabs\GotenbergBundle\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Client\GotenbergResponse;
use Sensiolabs\GotenbergBundle\Enum\PdfPart;
use Sensiolabs\GotenbergBundle\Exception\JsonEncodingException;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\Mime\Part\DataPart;

abstract class AbstractPdfBuilder implements PdfBuilderInterface
{
    /**
     * @var array<string, mixed>
     */
    protected array $formFields = [];

    private string|null $fileName = null;

    private string $headerDisposition = HeaderUtils::DISPOSITION_INLINE;

    /**
     * @var array<string, (\Closure(mixed): array<string, array<string|int ,mixed>|non-empty-string|int|float|bool|DataPart>)>
     */
    private array $normalizers;

    public function __construct(
        protected readonly GotenbergClientInterface $gotenbergClient,
        protected readonly AssetBaseDirFormatter $asset,
    ) {
        $this->normalizers = [
            'extraHttpHeaders' => function (mixed $value): array {
                return $this->encodeData('extraHttpHeaders', $value);
            },
            'assets' => static function (array $value): array {
                return ['files' => $value];
            },
            PdfPart::HeaderPart->value => static function (DataPart $value): array {
                return ['files' => $value];
            },
            PdfPart::BodyPart->value => static function (DataPart $value): array {
                return ['files' => $value];
            },
            PdfPart::FooterPart->value => static function (DataPart $value): array {
                return ['files' => $value];
            },
            'failOnHttpStatusCodes' => function (mixed $value): array {
                return $this->encodeData('failOnHttpStatusCodes', $value);
            },
            'cookies' => function (mixed $value): array {
                return $this->encodeData('cookies', array_values($value));
            },
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function encodeData(string $key, mixed $value): array
    {
        try {
            $encodedValue = json_encode($value, \JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            throw new JsonEncodingException(sprintf('Could not encode property "%s" into JSON', $key), previous: $exception);
        }

        return [$key => $encodedValue];
    }

    /**
     * The GotenbergPdf API endpoint path.
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

    public function generate(): GotenbergResponse
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
     * Compiles the form values into a multipart form data array to send to the HTTP client.
     *
     * @return array<int, array<string, string>>
     */
    public function getMultipartFormData(): array
    {
        $multipartFormData = [];

        foreach ($this->formFields as $key => $value) {
            $preCallback = null;

            if (\array_key_exists($key, $this->normalizers)) {
                $preCallback = $this->normalizers[$key](...);
            }

            foreach ($this->addToMultipart($key, $value, $preCallback) as $multiPart) {
                $multipartFormData[] = $multiPart;
            }
        }

        return $multipartFormData;
    }

    protected function addNormalizer(string $key, \Closure $normalizer): void
    {
        $this->normalizers[$key] = $normalizer;
    }

    /**
     * @param array<int|string, mixed>|string|int|float|bool|DataPart $value
     *
     * @return list<array<string, mixed>>
     */
    private function addToMultipart(string $key, array|string|int|float|bool|DataPart $value, \Closure|null $preCallback = null): array
    {
        if (null !== $preCallback) {
            $result = [];

            foreach ($preCallback($value) as $innerKey => $innerValue) {
                $result[] = $this->addToMultipart($innerKey, $innerValue);
            }

            return array_merge(...$result);
        }

        if (\is_bool($value)) {
            return [[
                $key => $value ? 'true' : 'false',
            ]];
        }

        if (\is_int($value) || \is_float($value)) {
            return [[
                $key => (string) $value,
            ]];
        }

        if (\is_array($value)) {
            $result = [];
            foreach ($value as $nestedValue) {
                $result[] = $this->addToMultipart($key, $nestedValue);
            }

            return array_merge(...$result);
        }

        return [[
            $key => $value,
        ]];
    }

    /**
     * @param non-empty-list<string> $validExtensions
     */
    protected function assertFileExtension(string $path, array $validExtensions): void
    {
        $file = new File($this->asset->resolve($path));
        $extension = $file->getExtension();

        if (!\in_array($extension, $validExtensions, true)) {
            throw new \InvalidArgumentException(sprintf('The file extension "%s" is not available in GotenbergPdf.', $extension));
        }
    }
}
