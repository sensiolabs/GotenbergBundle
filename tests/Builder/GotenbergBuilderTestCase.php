<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File;
use Symfony\Component\Mime\Part\TextPart;

/**
 * @template T of BuilderInterface
 */
abstract class GotenbergBuilderTestCase extends TestCase
{
    protected const FIXTURE_DIR = __DIR__.'/../Fixtures';

    protected GotenbergClientAsserter $client;
    protected ContainerInterface $dependencies;
    /** @var T */
    protected BuilderInterface $builder;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = new GotenbergClientAsserter();
        $this->dependencies = new Container();

        $this->builder = $this->createBuilder($this->client, $this->dependencies);
    }

    /**
     * @return T
     */
    abstract protected function createBuilder(GotenbergClientInterface $client, ContainerInterface $dependencies): BuilderInterface;

    /**
     * @return T
     */
    protected function getBuilder(): BuilderInterface
    {
        return $this->builder;
    }

    protected function assertGotenbergEndpoint(string $endpoint): void
    {
        $this->assertSame($endpoint, $this->client->getEndpoint());
    }

    protected function assertGotenbergFormData(string $name, string $value): void
    {
        foreach ($this->client->getBody() as $part) {
            if (!$part instanceof TextPart || $part->getName() !== $name) {
                continue;
            }

            if ($part->getBody() === $value) {
                $this->addToAssertionCount(1);

                return;
            }
        }
        $this->fail(\sprintf('No matching form data found with name "%s" and value "%s".', $name, $value));
    }

    protected function assertGotenbergFormDataFile(string $name, string $contentType, string $path): void
    {
        foreach ($this->client->getBody() as $part) {
            if (!$part instanceof DataPart || $part->getName() !== $name) {
                continue;
            }

            $body = \Closure::bind(static fn (TextPart $part) => $part->body, null, TextPart::class)($part);
            if ($part->getContentType() === $contentType && $body instanceof File && $body->getPath() === $path) {
                $this->addToAssertionCount(1);

                return;
            }
        }
        $this->fail(\sprintf('No matching form data file found with name "%s", content type "%s" and path "%s".', $name, $contentType, $path));
    }

    protected function assertGotenbergHeader(string $name, mixed $value): void
    {
        foreach ($this->client->getHeaders() as $header) {
            if ($header->getName() === $name && $header->getBodyAsString() === $value) {
                $this->addToAssertionCount(1);

                return;
            }
        }
        $this->fail(\sprintf('No matching header found with name "%s" and value "%s".', $name, $value));
    }

    protected function assertGotenbergException(string $message): void
    {
        $this->assertSame($message, $this->client->getThrowable()->getMessage());
    }

    protected function assertContentFile(string $filename, string $contentType = 'text/html', string|null $expectedContent = null): void
    {
        foreach ($this->client->getBody() as $part) {
            if (!$part instanceof DataPart || $part->getFilename() !== $filename) {
                continue;
            }

            self::assertSame($contentType, $part->getContentType());
            if (null !== $expectedContent) {
                self::assertSame($expectedContent, $part->getBody());
            }

            iterator_to_array($part->bodyToIterable());
        }
    }
}
