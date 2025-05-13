<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Psr\Log\LoggerInterface;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Exception\JsonEncodingException;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Sensiolabs\GotenbergBundle\Processor\NullProcessor;
use Sensiolabs\GotenbergBundle\Processor\ProcessorInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\Mime\Part\DataPart;

trait DefaultBuilderTrait
{
    private readonly GotenbergClientInterface $client;

    protected readonly AssetBaseDirFormatter $asset;

    /**
     * @var array<string, mixed>
     */
    protected array $formFields = [];

    /**
     * @var array<string, (\Closure(mixed): array<string, array<string|int, mixed>|string|\Stringable|int|float|bool|\BackedEnum|DataPart>)>
     */
    private array $normalizers = [];

    private string|null $fileName = null;

    private string $headerDisposition = HeaderUtils::DISPOSITION_INLINE;

    protected LoggerInterface|null $logger = null;

    /** @var ProcessorInterface<mixed>|null */
    private ProcessorInterface|null $processor;

    /**
     * @var \Closure(): string
     */
    private \Closure $traceGenerator;

    public function setLogger(LoggerInterface|null $logger): void
    {
        $this->logger = $logger;
    }

    /**
     * @param (\Closure(mixed): array<string, array<string|int, mixed>|string|\Stringable|int|float|bool|\BackedEnum|DataPart>) $normalizer
     */
    protected function addNormalizer(string $key, \Closure $normalizer): void
    {
        $this->normalizers[$key] = $normalizer;
    }

    /**
     * @return array<string, mixed>
     */
    protected function encodeData(string $key, mixed $value): array
    {
        try {
            $encodedValue = json_encode($value, \JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            throw new JsonEncodingException(\sprintf('Could not encode property "%s" into JSON', $key), previous: $exception);
        }

        return [$key => $encodedValue];
    }

    /**
     * @param HeaderUtils::DISPOSITION_* $headerDisposition
     */
    public function fileName(string $fileName, string $headerDisposition = HeaderUtils::DISPOSITION_INLINE): static
    {
        $this->fileName = $fileName;
        $this->headerDisposition = $headerDisposition;

        return $this;
    }

    /**
     * @param ProcessorInterface<mixed> $processor
     */
    public function processor(ProcessorInterface $processor): static
    {
        $this->processor = $processor;

        return $this;
    }

    /**
     * The Gotenberg API endpoint path.
     */
    abstract protected function getEndpoint(): string;

    /**
     * @param array<string, mixed> $configurations
     */
    abstract public function setConfigurations(array $configurations): static;

    /**
     * @param non-empty-list<string> $validExtensions eg: ['png', 'jpeg']
     */
    protected function assertFileExtension(string $path, array $validExtensions): void
    {
        $file = new File($this->asset->resolve($path));
        $extension = $file->getExtension();

        if (!\in_array($extension, $validExtensions, true)) {
            throw new \InvalidArgumentException(\sprintf('The file extension "%s" is not valid in this context.', $extension));
        }
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
            if (null === $value) {
                $this->logger?->debug('Key {sensiolabs_gotenberg.key} is null, skipping.', [
                    'sensiolabs_gotenberg.key' => $key,
                ]);

                continue;
            }

            $preCallback = null;

            if (\array_key_exists($key, $this->normalizers)) {
                $this->logger?->debug('Normalizer found for key {sensiolabs_gotenberg.key}.', [
                    'sensiolabs_gotenberg.key' => $key,
                ]);
                $preCallback = $this->normalizers[$key](...);
            }

            foreach ($this->convertToMultipartItems($key, $value, $preCallback) as $multiPart) {
                $multipartFormData[] = $multiPart;
            }
        }

        return $multipartFormData;
    }

    /**
     * @param array<int|string, mixed>|string|\Stringable|int|float|bool|\BackedEnum|DataPart $value
     *
     * @return list<array<string, mixed>>
     */
    private function convertToMultipartItems(string $key, array|string|\Stringable|int|float|bool|\BackedEnum|DataPart $value, \Closure|null $preCallback = null): array
    {
        if (null !== $preCallback) {
            $result = [];

            foreach ($preCallback($value) as $innerKey => $innerValue) {
                $result[] = $this->convertToMultipartItems($innerKey, $innerValue);
            }

            return array_merge(...$result);
        }

        if (\is_bool($value)) {
            return [[
                $key => $value ? 'true' : 'false',
            ]];
        }

        if (\is_int($value)) {
            return [[
                $key => (string) $value,
            ]];
        }

        if (\is_float($value)) {
            [$left, $right] = sscanf((string) $value, '%d.%s') ?? [$value, ''];

            $right ??= '0';

            return [[
                $key => "{$left}.{$right}",
            ]];
        }

        if ($value instanceof \BackedEnum) {
            return [[
                $key => (string) $value->value,
            ]];
        }

        if ($value instanceof \Stringable) {
            return [[
                $key => (string) $value,
            ]];
        }

        if (\is_array($value)) {
            $result = [];
            foreach ($value as $nestedValue) {
                $result[] = $this->convertToMultipartItems($key, $nestedValue);
            }

            return array_merge(...$result);
        }

        return [[
            $key => $value,
        ]];
    }

    public function generate(): GotenbergFileResult
    {
        $this->traceGenerator ??= $this::defaultTraceGenerator(...);
        $trace = ($this->traceGenerator)();
        $headers = ['Gotenberg-Trace' => $trace];

        $this->logger?->debug('Processing file with trace "{sensiolabs_gotenberg.trace}" using {sensiolabs_gotenberg.builder} builder.', [
            'sensiolabs_gotenberg.trace' => $trace,
            'sensiolabs_gotenberg.builder' => $this::class,
        ]);

        $processor = $this->processor ?? new NullProcessor();

        return new GotenbergFileResult(
            $this->client->call($this->getEndpoint(), $this->getMultipartFormData(), $headers),
            $processor($this->fileName),
            $this->headerDisposition,
            $this->fileName,
        );
    }

    /**
     * Sets the callable that will generate the trace ID for each operation.
     *
     * @param \Closure(): string $traceGenerator
     */
    public function traceGenerator(\Closure $traceGenerator): static
    {
        $this->traceGenerator = $traceGenerator;

        return $this;
    }

    protected static function defaultTraceGenerator(): string
    {
        return bin2hex(random_bytes(16)).microtime(true);
    }
}
