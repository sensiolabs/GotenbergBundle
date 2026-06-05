<?php

namespace Sensiolabs\GotenbergBundle\Builder\Util;

use Symfony\Component\Mime\Part\DataPart;

/**
 * DataPart whose body is generated lazily, chunk by chunk, while the request is sent.
 */
class StreamedDataPart extends DataPart
{
    /**
     * @param \Closure(): \Generator<mixed, scalar|\Stringable|null> $renderer
     */
    public function __construct(
        private readonly \Closure $renderer,
        string $filename,
        string $contentType,
    ) {
        parent::__construct('', $filename, $contentType);
    }

    public function getBody(): string
    {
        return implode('', iterator_to_array(($this->renderer)(), false));
    }

    public function bodyToString(): string
    {
        return $this->getBody();
    }

    /**
     * @return iterable<string>
     */
    public function bodyToIterable(): iterable
    {
        foreach (($this->renderer)() as $chunk) {
            yield (string) $chunk;
        }
    }
}
