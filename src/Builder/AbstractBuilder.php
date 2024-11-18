<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Psr\Container\ContainerInterface;
use Sensiolabs\GotenbergBundle\Builder\Result\GotenbergAsyncResult;
use Sensiolabs\GotenbergBundle\Builder\Result\GotenbergFileResult;
use Sensiolabs\GotenbergBundle\Client\BodyBag;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Client\HeadersBag;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Sensiolabs\GotenbergBundle\Processor\NullProcessor;
use Sensiolabs\GotenbergBundle\Processor\ProcessorInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File as DataPartFile;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Builder for responses.
 */
abstract class AbstractBuilder implements BuilderAsyncInterface, BuilderFileInterface
{
    private readonly BodyBag $bodyBag;
    private readonly HeadersBag $headersBag;

    private string $headerDisposition = HeaderUtils::DISPOSITION_INLINE;

    /** @var ProcessorInterface<mixed>|null */
    private ProcessorInterface|null $processor;

    public function __construct(
        protected readonly GotenbergClientInterface $client,
        protected readonly AssetBaseDirFormatter $asset,
        protected readonly ContainerInterface $dependencies = new Container(),
    ) {
        $bodyOptionsResolver = new OptionsResolver();
        $headersOptionsResolver = new OptionsResolver();

        $this->configure($bodyOptionsResolver, $headersOptionsResolver);

        $this->bodyBag = new BodyBag($bodyOptionsResolver);
        $this->headersBag = new HeadersBag($headersOptionsResolver);
    }

    /**
     * @param HeaderUtils::DISPOSITION_* $headerDisposition
     */
    public function fileName(string $fileName, string $headerDisposition = HeaderUtils::DISPOSITION_INLINE): static
    {
        $this->headerDisposition = $headerDisposition;

        $this->headersBag->set('Gotenberg-Output-Filename', $fileName);

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
     * Adds additional files, like images, fonts, stylesheets, and so on (overrides any previous files).
     */
    public function assets(string ...$paths): static
    {
        $this->bodyBag->unset('assets');

        foreach ($paths as $path) {
            $this->addAsset($path);
        }

        return $this;
    }

    /**
     * Adds a file, like an image, font, stylesheet, and so on.
     */
    public function addAsset(string $path): static
    {
        $resolvedPath = $this->asset->resolve($path);

        $dataPart = new DataPart(new DataPartFile($resolvedPath));
        $this->bodyBag->set('assets', [$resolvedPath => $dataPart] + $this->bodyBag->get('assets', []));

        return $this;
    }

    public function generate(): GotenbergFileResult
    {
        $response = $this->client->call($this->getEndpoint(), $this->bodyBag, $this->headersBag);

        return new GotenbergFileResult(
            $response->getStatusCode(),
            $response->getHeaders(),
            $this->client->stream($response),
            $this->processor ?? new NullProcessor(),
            $this->headerDisposition,
        );
    }

    public function generateAsync(): GotenbergAsyncResult
    {
        $response = $this->client->call($this->getEndpoint(), $this->bodyBag, $this->headersBag);

        return new GotenbergAsyncResult(
            $response->getStatusCode(),
            $response->getHeaders(),
        );
    }

    abstract protected function getEndpoint(): string;

    protected function configure(OptionsResolver $bodyOptionsResolver, OptionsResolver $headersOptionsResolver): void
    {
        $bodyOptionsResolver->setDefined([
            'assets',
        ]);
        $headersOptionsResolver->setDefined([
            'Gotenberg-Output-Filename',
        ]);
    }

    protected function getAsset(): AssetBaseDirFormatter
    {
        return $this->asset;
    }

    protected function getBodyBag(): BodyBag
    {
        return $this->bodyBag;
    }

    protected function getHeadersBag(): HeadersBag
    {
        return $this->headersBag;
    }
}
