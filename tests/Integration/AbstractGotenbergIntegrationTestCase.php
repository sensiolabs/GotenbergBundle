<?php

namespace Sensiolabs\GotenbergBundle\Tests\Integration;

use PHPUnit\Framework\Assert;
use Sensiolabs\GotenbergBundle\Builder\Result\GotenbergFileResult;
use Sensiolabs\GotenbergBundle\Processor\InMemoryProcessor;
use Sensiolabs\GotenbergBundle\Tests\Integration\Attributes\RequiresGotenberg;
use Sensiolabs\GotenbergBundle\Version\VersionFetcherInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class AbstractGotenbergIntegrationTestCase extends KernelTestCase
{
    private static string|null $resolvedGotenbergVersionUnderTest = null;

    protected static function getKernelClass(): string
    {
        return Kernel::class;
    }

    protected function setUp(): void
    {
        parent::setUp();

        self::requireIntegrationEnabled();
        $this->requireGotenbergVersionConstraints();
    }

    protected static function requireIntegrationEnabled(): void
    {
        if ('1' !== getenv('GOTENBERG_INTEGRATION_ENABLED')) {
            self::markTestSkipped('Gotenberg integration tests are disabled. Set GOTENBERG_INTEGRATION_ENABLED=1 to run them.');
        }
    }

    protected static function gotenbergVersionUnderTest(): string
    {
        self::requireIntegrationEnabled();

        $version = getenv('GOTENBERG_VERSION_UNDER_TEST');

        if (false === $version || '' === $version) {
            self::markTestSkipped('GOTENBERG_VERSION_UNDER_TEST is required for version-sensitive integration tests.');
        }

        return $version;
    }

    protected static function resolvedGotenbergVersionUnderTest(): string
    {
        if (null !== self::$resolvedGotenbergVersionUnderTest) {
            return self::$resolvedGotenbergVersionUnderTest;
        }

        /** @var VersionFetcherInterface $versionFetcher */
        $versionFetcher = static::getContainer()->get(VersionFetcherInterface::class);

        return self::$resolvedGotenbergVersionUnderTest = (string) $versionFetcher->get();
    }

    protected static function requireVersionAtLeast(string $version): void
    {
        self::requireGotenbergVersionRequirement(\sprintf('>= %s', $version));
    }

    protected static function requireVersionBelow(string $version): void
    {
        self::requireGotenbergVersionRequirement(\sprintf('< %s', $version));
    }

    protected function assertBinaryFileResult(
        GotenbergFileResult $result,
        string $expectedContentType,
        string|null $expectedFileExtension = null,
    ): void {
        if ('' === $expectedContentType) {
            self::fail('The expected content type must not be empty.');
        }

        Assert::assertSame(200, $result->getStatusCode());
        Assert::assertArrayHasKey('content-type', $result->getHeaders());

        /** @var non-empty-string $expectedContentType */
        Assert::assertStringStartsWith($expectedContentType, $result->getHeaders()['content-type'][0]);

        $fileName = $result->getFileName();
        if (null !== $expectedFileExtension && null !== $fileName) {
            if ('' === $expectedFileExtension) {
                self::fail('The expected file extension must not be empty when provided.');
            }

            /** @var non-empty-string $expectedFileExtension */
            Assert::assertStringEndsWith($expectedFileExtension, $fileName);
        }

        $content = $result->processor(new InMemoryProcessor())->process();

        Assert::assertIsString($content);
        Assert::assertNotSame('', $content);
    }

    private static function normalizeVersion(string $version): string
    {
        $parts = array_pad(explode('.', $version), 3, '0');

        return implode('.', \array_slice($parts, 0, 3));
    }

    private function requireGotenbergVersionConstraints(): void
    {
        $classReflection = new \ReflectionClass($this);
        $methodReflection = $classReflection->getMethod($this->name());

        foreach ([
            ...$classReflection->getAttributes(RequiresGotenberg::class),
            ...$methodReflection->getAttributes(RequiresGotenberg::class),
        ] as $attribute) {
            /** @var RequiresGotenberg $requirement */
            $requirement = $attribute->newInstance();
            self::requireGotenbergVersionRequirement($requirement->versionRequirement());
        }
    }

    private static function requireGotenbergVersionRequirement(string $versionRequirement): void
    {
        [$operator, $requiredVersion] = self::parseVersionRequirement($versionRequirement);
        $currentVersion = self::resolvedGotenbergVersionUnderTest();

        if (!version_compare(self::normalizeVersion($currentVersion), self::normalizeVersion($requiredVersion), $operator)) {
            self::markTestSkipped(\sprintf('Requires Gotenberg %s %s, current version is %s.', $operator, $requiredVersion, $currentVersion));
        }
    }

    /**
     * @return array{string, string}
     */
    private static function parseVersionRequirement(string $versionRequirement): array
    {
        if (1 === preg_match('/^(<=|>=|<|>|==|=|!=)\s*(.+)$/', trim($versionRequirement), $matches)) {
            $operator = '=' === $matches[1] ? '==' : $matches[1];

            return [$operator, trim($matches[2])];
        }

        return ['==', trim($versionRequirement)];
    }
}
