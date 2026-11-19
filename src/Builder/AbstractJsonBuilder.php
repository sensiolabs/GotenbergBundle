<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Builder\Result\GotenbergAsyncResult;
use Sensiolabs\GotenbergBundle\Builder\Result\GotenbergFileResult;
use Sensiolabs\GotenbergBundle\Builder\Result\GotenbergJsonResult;
use Sensiolabs\GotenbergBundle\Exception\LogicException;
use Sensiolabs\GotenbergBundle\Processor\ProcessorInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

/**
 * Base of the builders whose route answers with JSON instead of a file.
 *
 * @template TJson of array<mixed> = array<string, mixed>
 *
 * @extends AbstractBuilder<mixed>
 */
abstract class AbstractJsonBuilder extends AbstractBuilder
{
    /**
     * @return GotenbergJsonResult<TJson>
     */
    public function generate(): GotenbergJsonResult
    {
        $result = parent::generate();
        \assert($result instanceof GotenbergJsonResult);

        /** @var GotenbergJsonResult<TJson> $result */
        return $result;
    }

    public function generateAsync(): GotenbergAsyncResult
    {
        throw new LogicException(\sprintf('"%s" returns JSON and cannot run asynchronously, use "generate()" instead.', static::class));
    }

    protected function createFileResult(ResponseStreamInterface $stream, ProcessorInterface $processor, string $disposition): GotenbergFileResult
    {
        return new GotenbergJsonResult($stream, $processor, $disposition);
    }
}
