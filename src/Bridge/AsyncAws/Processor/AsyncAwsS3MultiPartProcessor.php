<?php

namespace Sensiolabs\GotenbergBundle\Bridge\AsyncAws\Processor;

use AsyncAws\S3\Result\CompleteMultipartUploadOutput;
use AsyncAws\S3\S3Client;
use Psr\Log\LoggerInterface;
use Sensiolabs\GotenbergBundle\Exception\ProcessorException;
use Sensiolabs\GotenbergBundle\Processor\ProcessorInterface;

/**
 * @implements ProcessorInterface<CompleteMultipartUploadOutput>
 */
final class AsyncAwsS3MultiPartProcessor implements ProcessorInterface
{
    private const MIN_PART_SIZE = 5 * 1024 * 1024;

    public function __construct(
        private readonly S3Client $s3Client,
        private readonly string $bucketName,
        private readonly LoggerInterface|null $logger = null,
    ) {
    }

    public function __invoke(string|null $fileName): \Generator
    {
        if (null === $fileName) {
            $fileName = uniqid('gotenberg_', true);
            $this->logger?->debug('{processor}: no filename given. Content will be dumped to "{file}".', ['processor' => self::class, 'file' => $fileName]);
        }

        $this->logger?->debug('{processor}: starting multi part upload of "{file}".', ['processor' => self::class, 'file' => $fileName]);
        $multipart = $this->s3Client->createMultipartUpload([
            'Bucket' => $this->bucketName,
            'Key' => $fileName,
        ]);

        $uploadId = $multipart->getUploadId();
        if (null === $uploadId) {
            throw new ProcessorException('Could not initiate a multi part upload');
        }

        $uploads = [];

        $partNumber = 0;
        $currentChunk = '';

        try {
            do {
                $chunk = yield;

                $currentChunk .= $chunk->getContent();

                if (mb_strlen($currentChunk, '8bit') < self::MIN_PART_SIZE) {
                    continue;
                }

                ++$partNumber;

                $this->logger?->debug('{processor}: {min_size_required} reached. Uploading part {upload_part_number}', ['processor' => self::class, 'min_size_required' => self::MIN_PART_SIZE, 'upload_part_number' => $partNumber]);
                $upload = $this->s3Client->uploadPart([
                    'Bucket' => $this->bucketName,
                    'Key' => $fileName,
                    'Body' => $currentChunk,
                    'PartNumber' => $partNumber,
                    'UploadId' => $uploadId,
                ]);

                $uploads[] = [
                    'PartNumber' => $partNumber,
                    'ETag' => $upload->getEtag(),
                ];

                $currentChunk = '';
            } while (!$chunk->isLast());

            if ('' !== $currentChunk) {
                ++$partNumber;

                $this->logger?->debug('{processor}: last chunk reached. Uploading leftover part {upload_part_number}', ['processor' => self::class, 'upload_part_number' => $partNumber]);
                $upload = $this->s3Client->uploadPart([
                    'Bucket' => $this->bucketName,
                    'Key' => $fileName,
                    'Body' => $currentChunk,
                    'PartNumber' => $partNumber,
                    'UploadId' => $uploadId,
                ]);

                $uploads[] = [
                    'PartNumber' => $partNumber,
                    'ETag' => $upload->getEtag(),
                ];
            }

            unset($currentChunk, $upload);

            $this->logger?->debug('{processor}: completing multi part upload of "{file}".', ['processor' => self::class, 'file' => $fileName]);

            return $this->s3Client->completeMultipartUpload([
                'UploadId' => $uploadId,
                'Bucket' => $this->bucketName,
                'Key' => $fileName,
                'MultipartUpload' => [
                    'Parts' => $uploads,
                ],
            ]);
        } catch (\Throwable $e) {
            $this->s3Client->abortMultipartUpload([
                'UploadId' => $uploadId,
                'Bucket' => $this->bucketName,
                'Key' => $fileName,
            ]);

            throw $e;
        }
    }
}
