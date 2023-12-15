<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder;

use Sensiolabs\GotenbergBundle\Pdf\GotenbergInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

trait BuilderTestTrait
{
    private const FIXTURE_DIR = __DIR__.'/../Fixtures';

    private function getGotenbergMock(): GotenbergInterface
    {
        return $this->createMock(GotenbergInterface::class);
    }

    private function getTwig(): Environment
    {
        return new Environment(new FilesystemLoader(self::FIXTURE_DIR.'/templates'));
    }
}
