<?php

namespace Sensiolabs\GotenbergBundle\Debug\Builder;

use Sensiolabs\GotenbergBundle\Builder\BuilderAsyncInterface;
use Sensiolabs\GotenbergBundle\Builder\BuilderFileInterface;
use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Result\GotenbergAsyncResult;
use Sensiolabs\GotenbergBundle\Builder\Result\GotenbergFileResult;
use Symfony\Component\Stopwatch\Stopwatch;

final class TraceableBuilder implements BuilderFileInterface, BuilderAsyncInterface
{
    /**
     * @var list<array{'type': 'sync'|'async', 'time': float|null, 'memory': int|null, 'size': int<0, max>|null, 'fileName': string|null, 'calls': list<array{'method': string, 'class': class-string<BuilderInterface>, 'arguments': array<mixed>}>}>
     */
    private array $files = [];

    /**
     * @var list<array{'class': class-string<BuilderInterface>, 'method': string, 'arguments': array<mixed>}>
     */
    private array $calls = [];

    private int $totalGenerated = 0;

    private static int $count = 0;

    public function __construct(
        private readonly BuilderFileInterface|BuilderAsyncInterface $inner,
        private readonly Stopwatch|null $stopwatch,
    ) {
    }

    public function generate(): GotenbergFileResult
    {
        if (!$this->inner instanceof BuilderFileInterface) {
            throw new \LogicException(\sprintf('The inner builder of %s must implement %s.', self::class, BuilderFileInterface::class));
        }

        $name = self::$count.'.'.$this->inner::class.'::'.__FUNCTION__;
        ++self::$count;

        $swEvent = $this->stopwatch?->start($name, 'gotenberg.generate_pdf');
        $response = $this->inner->generate();
        $swEvent?->stop();

        $this->files[] = [
            'type' => 'sync',
            'calls' => $this->calls,
            'time' => $swEvent?->getDuration(),
            'memory' => $swEvent?->getMemory(),
            'size' => $response->getContentLength(),
            'fileName' => $response->getFileName(),
        ];

        ++$this->totalGenerated;

        return $response;
    }

    public function generateAsync(): GotenbergAsyncResult
    {
        if (!$this->inner instanceof BuilderAsyncInterface) {
            throw new \LogicException(\sprintf('The inner builder of %s must implement %s.', self::class, BuilderAsyncInterface::class));
        }

        $name = self::$count.'.'.$this->inner::class.'::'.__FUNCTION__;
        ++self::$count;

        $swEvent = $this->stopwatch?->start($name, 'gotenberg.generate_pdf');
        $response = $this->inner->generateAsync();
        $swEvent?->stop();

        $this->files[] = [
            'type' => 'async',
            'calls' => $this->calls,
            'time' => $swEvent?->getDuration(),
            'memory' => $swEvent?->getMemory(),
            'size' => null,
            'fileName' => null,
        ];

        ++$this->totalGenerated;

        return $response;
    }

    /**
     * @param array<mixed> $arguments
     */
    public function __call(string $name, array $arguments): mixed
    {
        $result = $this->inner->$name(...$arguments);

        $this->calls[] = [
            'class' => $this->inner::class,
            'method' => $name,
            'arguments' => $arguments,
        ];

        if ($result === $this->inner) {
            return $this;
        }

        return $result;
    }

    /**
     * @return list<array{'type': 'sync'|'async', 'time': float|null, 'memory': int|null, 'size': int<0, max>|null, 'fileName': string|null, 'calls': list<array{'class': class-string<BuilderInterface>, 'method': string, 'arguments': array<mixed>}>}>
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    public function getInner(): BuilderFileInterface|BuilderAsyncInterface
    {
        return $this->inner;
    }
}
