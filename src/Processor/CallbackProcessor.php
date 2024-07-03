<?php

namespace Sensiolabs\GotenbergBundle\Processor;

use Symfony\Contracts\HttpClient\ChunkInterface;

/** *
 * @implements ProcessorInterface<void>
 */
final class CallbackProcessor implements ProcessorInterface
{
    /**
     * @param \Closure(ChunkInterface): void $callback
     */
    public function __construct(
        private readonly \Closure $callback,
    ) {
    }

    public function __invoke(string|null $fileName): \Generator
    {
        do {
            $chunk = yield;
            ($this->callback)($chunk);
        } while (!$chunk->isLast());
    }
}
