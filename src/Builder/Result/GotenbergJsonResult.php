<?php

namespace Sensiolabs\GotenbergBundle\Builder\Result;

use Sensiolabs\GotenbergBundle\Exception\ProcessorException;
use Sensiolabs\GotenbergBundle\Processor\InMemoryProcessor;

/**
 * Result of a route that answers with JSON instead of a file.
 *
 * @template TJson of array<mixed> = array<string, mixed>
 *
 * @extends GotenbergFileResult<mixed>
 */
class GotenbergJsonResult extends GotenbergFileResult
{
    /**
     * @var TJson|null
     */
    private array|null $decoded = null;

    /**
     * @return TJson
     */
    public function toArray(): array
    {
        if (null !== $this->decoded) {
            return $this->decoded;
        }

        try {
            $decoded = json_decode($this->readJson(), true, flags: \JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new ProcessorException('Gotenberg did not answer with valid JSON.', 0, $e);
        }

        if (!\is_array($decoded)) {
            throw new ProcessorException('Gotenberg did not answer with a JSON object.');
        }

        /** @var TJson $decoded */
        return $this->decoded = $decoded;
    }

    private function readJson(): string
    {
        return $this->processor(new InMemoryProcessor())->process();
    }
}
