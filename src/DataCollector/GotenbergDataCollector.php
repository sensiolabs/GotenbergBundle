<?php

namespace Sensiolabs\GotenbergBundle\DataCollector;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Debug\Builder\TraceableBuilder;
use Sensiolabs\GotenbergBundle\Debug\Client\TraceableGotenbergClient;
use Sensiolabs\GotenbergBundle\Debug\TraceableGotenbergPdf;
use Sensiolabs\GotenbergBundle\Debug\TraceableGotenbergScreenshot;
use Symfony\Component\DependencyInjection\ServiceLocator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpKernel\DataCollector\LateDataCollectorInterface;
use Symfony\Component\VarDumper\Caster\ArgsStub;
use Symfony\Component\VarDumper\Caster\CutArrayStub;
use Symfony\Component\VarDumper\Cloner\Data;

final class GotenbergDataCollector extends DataCollector implements LateDataCollectorInterface
{
    /**
     * @param ServiceLocator<TraceableBuilder|BuilderInterface> $builders
     * @param array<mixed>                                      $defaultOptions
     */
    public function __construct(
        private readonly TraceableGotenbergPdf $traceableGotenbergPdf,
        private readonly TraceableGotenbergScreenshot $traceableGotenbergScreenshot,
        private readonly ServiceLocator $builders,
        private readonly TraceableGotenbergClient $traceableGotenbergClient,
        private readonly array $defaultOptions,
    ) {
    }

    public function collect(Request $request, Response $response, \Throwable|null $exception = null): void
    {
        $this->data['request_total_size'] = 0;
        $this->data['request_total_time'] = 0;
        $this->data['request_count'] = 0;
        $this->data['builders'] = [];

        foreach ($this->builders->getProvidedServices() as $id => $type) {
            $builder = $this->builders->get($id);

            if ($builder instanceof TraceableBuilder) {
                $builder = $builder->getInner();
            }

            if (str_starts_with($id, '.sensiolabs_gotenberg.pdf_builder.')) {
                [$id] = sscanf($id, '.sensiolabs_gotenberg.pdf_builder.%s');
            } elseif (str_starts_with($id, '.sensiolabs_gotenberg.screenshot_builder.')) {
                [$id] = sscanf($id, '.sensiolabs_gotenberg.screenshot_builder.%s') ?? [$id];
            }

            $this->data['builders'][$id] = [
                'class' => $builder::class,
                'default_options' => $this->defaultOptions[$id] ?? [],
            ];
        }
    }

    public function getName(): string
    {
        return 'sensiolabs_gotenberg';
    }

    public function lateCollect(): void
    {
        $this->lateCollectFiles($this->traceableGotenbergPdf->getBuilders(), 'pdf');
        $this->lateCollectFiles($this->traceableGotenbergScreenshot->getBuilders(), 'screenshot');
    }

    /**
     * @param list<array{string, TraceableBuilder}> $builders
     */
    private function lateCollectFiles(array $builders, string $type): void
    {
        foreach ($builders as [$id, $builder]) {
            foreach ($builder->getFiles() as $request) {
                $this->data['request_total_time'] += $request['time'];
                $this->data['request_total_size'] += $request['size'] ?? 0;
                $this->data['files'][] = [
                    'builderClass' => $builder->getInner()::class,
                    'configuration' => [
                        'default_options' => $this->cloneVar($this->defaultOptions[$type][$id] ?? []),
                    ],
                    'payload' => $this->cloneVar(
                        $this->traceableGotenbergClient->getPayload()[$this->getRequestCount()] ?? [],
                    ),
                    'type' => $type,
                    'request_type' => $request['type'],
                    'time' => $request['time'],
                    'memory' => $request['memory'],
                    'size' => $this->formatSize($request['size'] ?? 0),
                    'fileName' => $request['fileName'],
                    'calls' => array_map(function (array $call): array {
                        $args = array_map(function (mixed $arg): mixed {
                            if (\is_array($arg)) {
                                return new CutArrayStub($arg, array_keys($arg));
                            }

                            return $arg;
                        }, $call['arguments']);

                        return [
                            'method' => $call['method'],
                            'stub' => $this->cloneVar(new ArgsStub($args, $call['method'], $call['class'])),
                        ];
                    }, $request['calls']),
                ];
            }

            $this->data['request_count'] += \count($builder->getFiles());
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
     * @return list<array{
     *      builderClass: string,
     *      type: string,
     *      time: float,
     *      memory: int,
     *      size: int<0, max>|null,
     *      fileName: string,
     *      configuration: array<string, array<mixed, mixed>>,
     *      payload: array<string, array<mixed, mixed>>,
     *      calls: list<array{
     *          method: string,
     *          stub: Data,
     *      }>
     * }>
     */
    public function getFiles(): array
    {
        return $this->data['files'] ?? [];
    }

    public function getRequestCount(): int
    {
        return $this->data['request_count'] ?? 0;
    }

    public function getRequestTotalTime(): int|float
    {
        return $this->data['request_total_time'] ?? 0.0;
    }

    /**
     * @return array{float, string}
     */
    public function getRequestTotalSize(): array
    {
        return $this->formatSize($this->data['request_total_size'] ?? 0);
    }
}
