<?php

namespace Sensiolabs\GotenbergBundle\Builder\Util;

use Symfony\Component\Mime\Part\DataPart;

/**
 * DataPart whose body is generated lazily, chunk by chunk, while the request is sent.
 */
class StreamedDataPart extends DataPart
{
    /**
     * Twig templates yield lots of tiny chunks (one per text node or expression). Yielding them
     * as-is makes curl send each one as a separate HTTP chunk, slowing the transfer down to the
     * point of hitting Gotenberg's API timeout. Chunks are buffered to this size before being yielded.
     */
    private const CHUNK_SIZE = 16384;

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
        $buffer = '';
        foreach (($this->renderer)() as $chunk) {
            $buffer .= $chunk;
            if (\strlen($buffer) >= self::CHUNK_SIZE) {
                yield $buffer;
                $buffer = '';
            }
        }

        if ('' !== $buffer) {
            yield $buffer;
        }
    }
}
