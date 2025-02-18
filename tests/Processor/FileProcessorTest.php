<?php

namespace Sensiolabs\GotenbergBundle\Tests\Processor;

use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Processor\FileProcessor;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpClient\Chunk\DataChunk;
use Symfony\Component\HttpClient\Chunk\FirstChunk;
use Symfony\Component\HttpClient\Chunk\LastChunk;

class FileProcessorTest extends TestCase
{
    public function testProcess(): void
    {
        $processor = new FileProcessor(new Filesystem(), sys_get_temp_dir());
        $generator = $processor(null);

        $generator->send(new FirstChunk(0, 'a'));
        $generator->send(new DataChunk(1, 'b'));
        $generator->send(new LastChunk(2, 'c'));

        $return = $generator->getReturn();

        self::assertInstanceOf(\SplFileInfo::class, $return);
        self::assertSame('abc', (string) $return->openFile());
    }
}
