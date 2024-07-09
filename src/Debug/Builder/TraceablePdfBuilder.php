<?php

namespace Sensiolabs\GotenbergBundle\Debug\Builder;

use Sensiolabs\GotenbergBundle\Builder\GotenbergFileResult;
use Sensiolabs\GotenbergBundle\Builder\Pdf\PdfBuilderInterface;
use Symfony\Component\Stopwatch\Stopwatch;

final class TraceablePdfBuilder implements PdfBuilderInterface
{
    /**
     * @var list<array{'time': float|null, 'memory': int|null, 'size': int<0, max>|null, 'fileName': string|null, 'calls': list<array{'method': string, 'class': class-string<PdfBuilderInterface>, 'arguments': array<mixed>}>}>
     */
    private array $pdfs = [];

    /**
     * @var list<array{'class': class-string<PdfBuilderInterface>, 'method': string, 'arguments': array<mixed>}>
     */
    private array $calls = [];

    private int $totalGenerated = 0;

    private static int $count = 0;

    public function __construct(
        private readonly PdfBuilderInterface $inner,
        private readonly Stopwatch|null $stopwatch,
    ) {
    }

    public function generate(): GotenbergFileResult
    {
        $name = self::$count.'.'.$this->inner::class.'::'.__FUNCTION__;
        ++self::$count;

        $swEvent = $this->stopwatch?->start($name, 'gotenberg.generate_pdf');
        $response = $this->inner->generate();
        $swEvent?->stop();

        $this->pdfs[] = [
            'calls' => $this->calls,
            'time' => $swEvent?->getDuration(),
            'memory' => $swEvent?->getMemory(),
            'size' => $response->getContentLength(),
            'fileName' => $response->getFileName(),
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
     * @return list<array{'time': float|null, 'memory': int|null, 'size': int<0, max>|null, 'fileName': string|null, 'calls': list<array{'class': class-string<PdfBuilderInterface>, 'method': string, 'arguments': array<mixed>}>}>
     */
    public function getFiles(): array
    {
        return $this->pdfs;
    }

    public function getInner(): PdfBuilderInterface
    {
        return $this->inner;
    }
}
