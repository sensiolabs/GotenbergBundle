<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder;

use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Filesystem\Path;
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
    protected Container $dependencies;
    /** @var T */
    protected BuilderInterface $builder;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = new GotenbergClientAsserter();
        $this->dependencies = new Container();

        $this->dependencies->set('asset_base_dir_formatter', new AssetBaseDirFormatter(static::FIXTURE_DIR, static::FIXTURE_DIR));
    }

    /**
     * @return T
     */
    abstract protected function createBuilder(GotenbergClientInterface $client, Container $dependencies): BuilderInterface;

    /**
     * @return T
     */
    protected function getBuilder(): BuilderInterface
    {
        return $this->builder ??= $this->createBuilder($this->client, $this->dependencies);
    }

    protected function getDependencies(): Container
    {
        return $this->dependencies;
    }

    protected function assertGotenbergEndpoint(string $endpoint): void
    {
        $this->assertSame($endpoint, $this->client->getEndpoint());
    }

    protected function assertGotenbergFormData(string $name, string $value): void
    {
        $availableNames = [];
        $found = false;
        $matches = false;
        $expected = null;

        /** @var TextPart|DataPart $part */
        foreach ($this->client->getBody() as $part) {
            $availableNames[] = $part->getName();

            if ($part->getName() !== $name) {
                continue;
            }
            $found = true;

            $expected = trim($part->getBody());
            if (trim($value) === $expected) {
                $this->addToAssertionCount(1);

                $matches = true;
            }
        }

        if (false === $found) {
            $this->fail(\sprintf('No matching form data with name "%s". Did you mean one of "%s" ?', $name, implode(', ', $availableNames)));
        }

        if (false === $matches) {
            $this->fail(\sprintf('No matching form data with name "%s" and value "%s". Expected "%s".', $name, $value, $expected));
        }
    }

    protected function assertGotenbergFormDataFile(string $name, string $contentType, string $path): void
    {
        foreach ($this->client->getBody() as $part) {
            if (!$part instanceof DataPart) {
                continue;
            }

            $body = \Closure::bind(static fn (DataPart $part) => $part->body, null, TextPart::class)($part);
            if ($part->getContentType() === $contentType && $body instanceof File && $body->getPath() === Path::canonicalize($path)) {
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
