<?php

namespace Sensiolabs\GotenbergBundle\RemoteEvent;

use Symfony\Component\RemoteEvent\RemoteEvent;

class SuccessGotenbergEvent extends RemoteEvent
{
    public const SUCCESS = 'success';

    /**
     * @param resource $file
     */
    public function __construct(
        string $id,
        mixed $file,
        private readonly string $filename,
        private readonly string $contentType,
        private readonly int $contentLength,
    ) {
        $payload['file'] = $file;

        parent::__construct(self::SUCCESS, $id, $payload);
    }

    /**
     * @return resource
     */
    public function getFile(): mixed
    {
        return $this->getPayload()['file'];
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function getContentType(): string
    {
        return $this->contentType;
    }

    public function getContentLength(): int
    {
        return $this->contentLength;
    }
}
