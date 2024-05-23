<?php

namespace Sensiolabs\GotenbergBundle\DataCollector;

use Sensiolabs\GotenbergBundle\Builder\Pdf\PdfBuilderInterface;
use Sensiolabs\GotenbergBundle\Debug\Builder\TraceablePdfBuilder;
use Sensiolabs\GotenbergBundle\Debug\TraceableGotenbergPdf;
use Symfony\Component\DependencyInjection\ServiceLocator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpKernel\DataCollector\LateDataCollectorInterface;
use Symfony\Component\VarDumper\Caster\ArgsStub;
use Symfony\Component\VarDumper\Cloner\Data;

final class GotenbergDataCollector extends DataCollector implements LateDataCollectorInterface
{
    /**
     * @param ServiceLocator<PdfBuilderInterface> $builders
     * @param array<mixed>                        $defaultOptions
     */
    public function __construct(
        private readonly TraceableGotenbergPdf $traceableGotenberg,
        private readonly ServiceLocator        $builders,
        private readonly array                 $defaultOptions,
    ) {
    }

    public function collect(Request $request, Response $response, \Throwable|null $exception = null): void
    {
        $this->data['request_total_memory'] = 0;
        $this->data['request_total_size'] = 0;
        $this->data['request_total_time'] = 0;
        $this->data['request_count'] = 0;
        $this->data['builders'] = [];

        foreach ($this->builders->getProvidedServices() as $id => $type) {
            $builder = $this->builders->get($id);

            if ($builder instanceof TraceablePdfBuilder) {
                $builder = $builder->getInner();
            }

            if (str_starts_with($id, '.sensiolabs_gotenberg.pdf_builder.')) {
                [$id] = sscanf($id, '.sensiolabs_gotenberg.pdf_builder.%s');
            }

            $this->data['builders'][$id] = [
                'class' => $builder::class,
                'default_options' => $this->defaultOptions[$id] ?? [],
                'pdfs' => [],
            ];
        }
    }

    public function getName(): string
    {
        return 'sensiolabs_gotenberg';
    }

    public function lateCollect(): void
    {
        /**
         * @var string              $id
         * @var TraceablePdfBuilder $builder
         */
        foreach ($this->traceableGotenberg->getBuilders() as [$id, $builder]) {
            $this->data['builders'][$id]['pdfs'] = array_merge(
                $this->data['builders'][$id]['pdfs'],
                array_map(function (array $request): array {
                    $this->data['request_total_time'] += $request['time'];
                    $this->data['request_total_memory'] += $request['memory'];
                    $this->data['request_total_size'] += $request['size'] ?? 0;

                    return [
                        'time' => $request['time'],
                        'memory' => $request['memory'],
                        'size' => $this->formatSize($request['size'] ?? 0),
                        'fileName' => $request['fileName'],
                        'calls' => array_map(function (array $call): array {
                            return [
                                'method' => $call['method'],
                                'stub' => $this->cloneVar(new ArgsStub($call['arguments'], $call['method'], $call['class'])),
                            ];
                        }, $request['calls']),
                    ];
                }, $builder->getPdfs()),
            );

            $this->data['request_count'] += \count($builder->getPdfs());
        }
    }

    /**
     * @param int<0, max> $size
     *
     * @return array{float, string}
     */
    private function formatSize(int $size): array
    {
        return match (true) {
            ($size / 1024 < 1) => [$size, 'B'],
            ($size / (1024 ** 2) < 1) => [round($size / 1024, 2), 'kB'],
            ($size / (1024 ** 3) < 1) => [round($size / (1024 ** 2), 2), 'MB'],
            ($size / (1024 ** 4) < 1) => [round($size / (1024 ** 3), 2), 'GB'],
            ($size / (1024 ** 5) < 1) => [round($size / (1024 ** 4), 2), 'TB'],
            default => throw new \LogicException('File too big'),
        };
    }

    /**
     * @return array<string, array{
     *     'class': string,
     *     'default_options': array<mixed>,
     *     'pdfs': list<array{
     *         'time': float,
     *         'memory': int,
     *         'size': int<0, max>|null,
     *         'fileName': string,
     *         'calls': list<array{
     *             'method': string,
     *             'stub': Data
     *         }>
     *     }>
     * }>
     */
    public function getBuilders(): array
    {
        return $this->data['builders'] ?? [];
    }

    public function getRequestCount(): int
    {
        return $this->data['request_count'] ?? 0;
    }

    public function getRequestTotalTime(): int|float
    {
        return $this->data['request_total_time'] ?? 0.0;
    }

    public function getRequestTotalMemory(): int
    {
        return $this->data['request_total_memory'] ?? 0;
    }

    /**
     * @return array{float, string}
     */
    public function getRequestTotalSize(): array
    {
        return $this->formatSize($this->data['request_total_size'] ?? 0);
    }
}
