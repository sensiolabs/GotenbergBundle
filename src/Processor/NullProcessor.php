<?php

namespace Sensiolabs\GotenbergBundle\Processor;

/**
 * @implements ProcessorInterface<void>
 */
final class NullProcessor implements ProcessorInterface
{
    public function __invoke(string|null $fileName): \Generator
    {
        yield;
    }
}
