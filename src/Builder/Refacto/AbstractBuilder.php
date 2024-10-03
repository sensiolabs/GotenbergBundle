<?php

namespace Sensiolabs\GotenbergBundle\Builder\Refacto;

use Psr\Log\LoggerInterface;
use Sensiolabs\GotenbergBundle\Builder\GotenbergFileResult;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Exception\JsonEncodingException;
use Sensiolabs\GotenbergBundle\Processor\NullProcessor;
use Sensiolabs\GotenbergBundle\Processor\ProcessorInterface;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractBuilder
{
    private readonly FormDataHandler $formDataHandler;

    private string|null $fileName = null;

    private string $headerDisposition = HeaderUtils::DISPOSITION_INLINE;

    private ProcessorInterface|null $processor;

    public function __construct(
        private readonly GotenbergClientInterface $client,
        protected readonly LoggerInterface|null $logger = null,
    )
    {
        $this->configure($optionResolver = new OptionsResolver());
        $this->formDataHandler = new FormDataHandler($optionResolver);
    }

    // CONFIGURATION
    abstract protected function getEndpoint(): string;

    abstract protected function configure(OptionsResolver $optionsResolver): void;


    // SETTER
    public function fileName(string $fileName, string $headerDisposition = HeaderUtils::DISPOSITION_INLINE): static
    {
        $this->fileName = $fileName;
        $this->headerDisposition = $headerDisposition;

        return $this;
    }

    public function processor(ProcessorInterface $processor): static
    {
        $this->processor = $processor;

        return $this;
    }

    // GENERATE
    public function generate(): GotenbergFileResult
    {
        $this->logger?->debug('Processing file using {sensiolabs_gotenberg.builder} builder.', [
            'sensiolabs_gotenberg.builder' => $this::class,
        ]);

        $endpoint = $this->getEndpoint();
        $processor = $this->processor ?? new NullProcessor();

        return new GotenbergFileResult(
            $this->client->call($endpoint, $this->formDataHandler->getMultipartFormData()),
            $processor($this->fileName),
            $this->headerDisposition,
            $this->fileName,
        );
    }

    // INTERNAL
    protected function setData(string $name, mixed $value): static
    {
        $this->formDataHandler->setData($name, $value);

        return $this;
    }
}
