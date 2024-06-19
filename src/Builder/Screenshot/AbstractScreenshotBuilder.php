<?php

namespace Sensiolabs\GotenbergBundle\Builder\Screenshot;

use Psr\Log\LoggerInterface;
use Sensiolabs\GotenbergBundle\Builder\AsyncBuilderInterface;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Client\GotenbergResponse;
use Sensiolabs\GotenbergBundle\DependencyInjection\WebhookConfigurationRegistry;
use Sensiolabs\GotenbergBundle\Enumeration\Part;
use Sensiolabs\GotenbergBundle\Exception\JsonEncodingException;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\Mime\Part\DataPart;

abstract class AbstractScreenshotBuilder implements ScreenshotBuilderInterface, AsyncBuilderInterface
{
    protected LoggerInterface|null $logger = null;

    /**
     * @var array<string, mixed>
     */
    protected array $formFields = [];

    private string|null $fileName = null;

    private string $headerDisposition = HeaderUtils::DISPOSITION_INLINE;

    /**
     * @var array<string, (\Closure(mixed): array<string, array<string|int, mixed>|non-empty-string|\Stringable|int|float|bool|\BackedEnum|DataPart>)>
     */
    private array $normalizers;
    private string $webhookUrl;
    private string $errorWebhookUrl;
    /**
     * @var array<string, mixed>
     */
    private array $webhookExtraHeaders = [];
    private \Closure $operationIdGenerator;

    public function __construct(
        protected readonly GotenbergClientInterface $gotenbergClient,
        protected readonly AssetBaseDirFormatter $asset,
        protected readonly WebhookConfigurationRegistry|null $webhookConfigurationRegistry = null,
    ) {
        $this->normalizers = [
            'extraHttpHeaders' => function (mixed $value): array {
                return $this->encodeData('extraHttpHeaders', $value);
            },
            'assets' => static function (array $value): array {
                return ['files' => $value];
            },
            Part::Body->value => static function (DataPart $value): array {
                return ['files' => $value];
            },
            'failOnHttpStatusCodes' => function (mixed $value): array {
                return $this->encodeData('failOnHttpStatusCodes', $value);
            },
            'cookies' => function (mixed $value): array {
                return $this->encodeData('cookies', array_values($value));
            },
        ];

        $this->operationIdGenerator = fn () => uniqid('gotenberg_', true);
    }

    public function setLogger(LoggerInterface|null $logger): void
    {
        $this->logger = $logger;
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
        $this->logger?->debug('Generating Screenshot file using {sensiolabs_gotenberg.builder} builder.', [
            'sensiolabs_gotenberg.builder' => $this::class,
        ]);

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

    public function generateAsync(): string
    {
        $operationId = ($this->operationIdGenerator)();
        $this->logger?->debug('Generating PDF file async with operation id {sensiolabs_gotenberg.operation_id} using {sensiolabs_gotenberg.builder} builder.', [
            'sensiolabs_gotenberg.operation_id' => $operationId,
            'sensiolabs_gotenberg.builder' => $this::class,
        ]);

        $this->webhookExtraHeaders['X-Gotenberg-Operation-Id'] = $operationId;
        $headers = [
            'Gotenberg-Webhook-Url' => $this->webhookUrl,
            'Gotenberg-Webhook-Error-Url' => $this->errorWebhookUrl,
            'Gotenberg-Webhook-Extra-Http-Headers' => json_encode($this->webhookExtraHeaders, \JSON_THROW_ON_ERROR),
        ];
        if (null !== $this->fileName) {
            $headers['Gotenberg-Output-Filename'] = basename($this->fileName, '.pdf');
        }
        $this->gotenbergClient->call($this->getEndpoint(), $this->getMultipartFormData(), $headers);

        return $operationId;
    }

    public function withWebhookConfiguration(string $webhook): static
    {
        if (null === $this->webhookConfigurationRegistry) {
            throw new \LogicException('The WebhookConfigurationRegistry is not available.');
        }
        $webhookConfiguration = $this->webhookConfigurationRegistry->get($webhook);

        return $this->withWebhookUrls($webhookConfiguration['success'], $webhookConfiguration['error']);
    }

    public function withWebhookUrls(string $successWebhook, string|null $errorWebhook = null): static
    {
        $clone = clone $this;
        $clone->webhookUrl = $successWebhook;
        $clone->errorWebhookUrl = $errorWebhook ?? $successWebhook;

        return $clone;
    }

    /**
     * @param array<string, mixed> $extraHeaders
     */
    public function withWebhookExtraHeaders(array $extraHeaders): static
    {
        $clone = clone $this;
        $clone->webhookExtraHeaders = array_merge($this->webhookExtraHeaders, $extraHeaders);

        return $clone;
    }

    public function withOperationIdGenerator(\Closure $operationIdGenerator): static
    {
        $clone = clone $this;
        $clone->operationIdGenerator = $operationIdGenerator;

        return $clone;
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
     * @param array<int|string, mixed>|string|\Stringable|int|float|bool|\BackedEnum|DataPart $value
     *
     * @return list<array<string, mixed>>
     */
    private function addToMultipart(string $key, array|string|\Stringable|int|float|bool|\BackedEnum|DataPart $value, \Closure|null $preCallback = null): array
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
