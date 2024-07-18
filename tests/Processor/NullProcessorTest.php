<?php

declare(strict_types=1);

namespace Processor;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Processor\FileProcessor;
use Sensiolabs\GotenbergBundle\Processor\NullProcessor;
use Symfony\Component\HttpClient\Chunk\DataChunk;
use Symfony\Component\HttpClient\Chunk\FirstChunk;
use Symfony\Component\HttpClient\Chunk\LastChunk;

#[CoversClass(FileProcessor::class)]
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
