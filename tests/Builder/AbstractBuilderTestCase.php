<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder;

use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

abstract class AbstractBuilderTestCase extends TestCase
{
    protected const FIXTURE_DIR = __DIR__.'/../Fixtures';

    protected static Environment $twig;

    public static function setUpBeforeClass(): void
    {
        self::$twig = new Environment(new FilesystemLoader(self::FIXTURE_DIR.'/templates'));
    }
}
