<?php

declare(strict_types=1);

namespace Sensiolabs\GotenbergBundle\DataCollector;

use Sensiolabs\GotenbergBundle\Builder\PdfBuilderInterface;
use Sensiolabs\GotenbergBundle\Debug\Builder\TraceablePdfBuilder;
use Sensiolabs\GotenbergBundle\Debug\TraceableGotenberg;
use Symfony\Component\DependencyInjection\ServiceLocator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpKernel\DataCollector\LateDataCollectorInterface;
use Symfony\Component\VarDumper\Cloner\Data;
use function sscanf;
use function str_starts_with;

final class GotenbergDataCollector extends DataCollector implements LateDataCollectorInterface
{
    /**
     * @param ServiceLocator<PdfBuilderInterface> $builders
     */
    public function __construct(
        private readonly TraceableGotenberg $traceableGotenberg,
        private readonly ServiceLocator $builders,
        private readonly array $defaultOptions,
    ) {
    }

    public function collect(Request $request, Response $response, ?\Throwable $exception = null): void
    {
        $this->data['request_count'] = 0;
        $this->data['builders'] = [];

        foreach ($this->builders->getProvidedServices() as $id => $type) {
            $builder = $this->builders->get($id);

            if ($builder instanceof TraceablePdfBuilder) {
                $builder = $builder->getInner();
            }

            if (str_starts_with($id, '.sensiolabs_gotenberg.builder.')) {
                [$id] = sscanf($id, '.sensiolabs_gotenberg.builder.%s');
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
            $this->data['builders'][$id]['pdfs'] = array_merge($this->data['builders'][$id]['pdfs'], \array_map(function (array $request): array {
                $request['calls'] = \array_map(function (array $call): array {
                    return \array_merge($call, ['stub' => $this->cloneVar($call['stub'])]);
                }, $request['calls']);

                return $request;
            }, $builder->getPdfs()));

            $this->data['request_count'] += \count($builder->getPdfs());
        }
    }

    /**
     * @return array<string, array{
     *     'class': string,
     *     'default_options': array<mixed>,
     *     'pdfs': list<array{
     *         'time': float,
     *         'fileName': string,
     *         'calls': list<array{
     *             'method': string,
     *             'arguments': array<mixed>,
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
}
