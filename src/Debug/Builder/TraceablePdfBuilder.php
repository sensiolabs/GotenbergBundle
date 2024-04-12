<?php

declare(strict_types=1);

namespace Sensiolabs\GotenbergBundle\Debug\Builder;

use Sensiolabs\GotenbergBundle\Builder\PdfBuilderInterface;
use Sensiolabs\GotenbergBundle\Client\PdfResponse;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\VarDumper\Caster\ArgsStub;
use Symfony\Component\VarDumper\Cloner\Stub;

final class TraceablePdfBuilder implements PdfBuilderInterface
{
    /**
     * @var list<array{'time': float, 'memory': int, 'fileName': string, 'calls': list<array{'method': string, 'arguments': array<mixed>, 'stub': Stub}>}>
     */
    private array $pdfs = [];

    /**
     * @var list<array{'method': string, 'arguments': array<mixed>, 'stub': Stub}>
     */
    private array $calls = [];

    private int $totalGenerated = 0;

    private static int $count = 0;

    public function __construct(
        private readonly PdfBuilderInterface $inner,
        private readonly Stopwatch $stopwatch,
    ) {
    }

    public function generate(): PdfResponse
    {
        $name = self::$count.'.'.$this->inner::class.'::'.__FUNCTION__;
        ++self::$count;

        $swEvent = $this->stopwatch->start($name, 'gotenberg.generate_pdf');
        $response = $this->inner->generate();
        $swEvent->stop();

        $fileName = 'Unknown.pdf';
        if ($response->headers->has('Content-Disposition')) {
            $matches = [];

            preg_match('#[^;]*;\sfilename="?(?P<fileName>[^"]*)"?#', $response->headers->get('Content-Disposition', ''), $matches);
            $fileName = $matches['fileName'];
        }

        $this->pdfs[] = [
            'calls' => $this->calls,
            'time' => $swEvent->getDuration(),
            'memory' => $swEvent->getMemory(),
            'fileName' => $fileName,
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
            'method' => $name,
            'arguments' => $arguments,
            'stub' => new ArgsStub($arguments, $name, $this->inner::class),
        ];

        if ($result === $this->inner) {
            return $this;
        }

        return $result;
    }

    /**
     * @return list<array{'time': float, 'memory': int, 'fileName': string, 'calls': list<array{'method': string, 'arguments': array<mixed>, 'stub': Stub}>}>
     */
    public function getPdfs(): array
    {
        return $this->pdfs;
    }

    public function getInner(): PdfBuilderInterface
    {
        return $this->inner;
    }
}
