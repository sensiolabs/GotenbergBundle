<?php

namespace Sensiolabs\GotenbergBundle\Tests\Processor;

use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Processor\NullProcessor;
use Symfony\Component\HttpClient\Chunk\DataChunk;
use Symfony\Component\HttpClient\Chunk\FirstChunk;
use Symfony\Component\HttpClient\Chunk\LastChunk;

class NullProcessorTest extends TestCase
{
    public function testProcess(): void
    {
        $processor = new NullProcessor();
        $generator = $processor(null);

        $generator->send(new FirstChunk(0, 'a'));
        $generator->send(new DataChunk(1, 'b'));
        $generator->send(new LastChunk(2, 'c'));

        $return = $generator->getReturn();

        self::assertNull($return);
    }
}
