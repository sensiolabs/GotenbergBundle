<?php

namespace Sensiolabs\GotenbergBundle\Processor;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Sensiolabs\GotenbergBundle\Exception\ProcessorException;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @implements ProcessorInterface<string>
 */
final class FileProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly Filesystem $filesystem,
        private readonly string $directory,
        private readonly LoggerInterface $logger = new NullLogger(),
    ) {
    }

    public function __invoke(string|null $fileName): \Generator
    {
        if (null === $fileName) {
            $fileName = uniqid('gotenberg_', true).'pdf';
            $this->logger->debug('{processor}: no filename given. Content will be dumped to "{file}".', ['processor' => self::class, 'file' => $fileName]);
        }

        $resource = tmpfile() ?: throw new ProcessorException('Unable to create a temporary file resource.');

        do {
            $chunk = yield;
            fwrite($resource, $chunk->getContent());
        } while (!$chunk->isLast());

        rewind($resource);

        $path = $this->directory.'/'.$fileName;

        $this->filesystem->dumpFile($path, $resource);
        $this->logger->debug('{processor}: content dumped to "{file}".', ['processor' => self::class, 'file' => $fileName]);

        fclose($resource);

        return new \SplFileInfo($path);
    }
}
