<?php

namespace Sensiolabs\GotenbergBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Inspired by Symfony's AbstractWebTestCase.
 *
 * @see https://github.com/symfony/symfony/blob/7.4/src/Symfony/Bundle/FrameworkBundle/Tests/Functional/AbstractWebTestCase.php
 * /
 */
abstract class AbstractGotenbergWebTestCase extends WebTestCase
{
    public static function setUpBeforeClass(): void
    {
        self::deleteTmpDir();
    }

    public static function tearDownAfterClass(): void
    {
        self::deleteTmpDir();
    }

    /**
     * @param array{test_case?: string} $options
     */
    protected static function createKernel(array $options = []): KernelInterface
    {
        if (!$testCase = $options['test_case'] ?? null) {
            throw new \InvalidArgumentException('The option "test_case" must be set.');
        }

        if (!is_dir(__DIR__.'/'.$testCase)) {
            throw new \InvalidArgumentException(\sprintf('The test case "%s" does not exist.', $testCase));
        }

        return new TestKernel(__DIR__.'/'.$testCase, self::getTmpDir());
    }

    protected static function getKernelClass(): string
    {
        return TestKernel::class;
    }

    private static function deleteTmpDir(): void
    {
        if (!file_exists($dir = self::getTmpDir())) {
            return;
        }

        (new Filesystem())->remove($dir);
    }

    private static function getTmpDir(): string
    {
        return sys_get_temp_dir().'/GotenbergBundle/'.substr(strrchr(static::class, '\\') ?: static::class, 1);
    }
}
