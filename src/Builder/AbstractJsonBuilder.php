<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Builder\Result\GotenbergAsyncResult;
use Sensiolabs\GotenbergBundle\Builder\Result\GotenbergJsonResult;
use Sensiolabs\GotenbergBundle\Exception\LogicException;
use Sensiolabs\GotenbergBundle\Processor\NullProcessor;
use Symfony\Component\HttpFoundation\HeaderUtils;

/**
 * Base of the builders whose route answers with JSON instead of a file.
 *
 * @extends AbstractBuilder<mixed>
 */
abstract class AbstractJsonBuilder extends AbstractBuilder
{
    public function generate(): GotenbergJsonResult
    {
        return new GotenbergJsonResult(
            $this->getClient()->stream($this->sendRequest()),
            new NullProcessor(),
            HeaderUtils::DISPOSITION_INLINE,
        );
    }

    public function generateAsync(): GotenbergAsyncResult
    {
        throw new LogicException(\sprintf('"%s" returns JSON and cannot run asynchronously, use "generate()" instead.', static::class));
    }
}
