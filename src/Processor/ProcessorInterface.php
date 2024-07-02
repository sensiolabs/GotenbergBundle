<?php

namespace Sensiolabs\GotenbergBundle\Processor;

use Symfony\Contracts\HttpClient\ChunkInterface;

/**
 * @template T
 */
interface ProcessorInterface
{
    /**
     * @return \Generator<int, null, ChunkInterface, T>
     */
    public function __invoke(string|null $fileName): \Generator;
}
