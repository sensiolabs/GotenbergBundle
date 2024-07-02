<?php

namespace Sensiolabs\GotenbergBundle\Processor;

/**
 * @implements ProcessorInterface<list<mixed>>
 */
final class ChainProcessor implements ProcessorInterface
{
    /**
     * @param list<ProcessorInterface<mixed>> $processors
     */
    public function __construct(
        private readonly array $processors,
    ) {
    }

    public function __invoke(string|null $fileName): \Generator
    {
        $generators = [];
        foreach ($this->processors as $processor) {
            $generators[] = ($processor)($fileName);
        }

        do {
            $chunk = yield;
            foreach ($generators as $generator) {
                $generator->send($chunk);
            }
        } while (!$chunk->isLast());

        return array_map(static fn (\Generator $generator): mixed => $generator->getReturn(), $generators);
    }
}
