<?php

declare(strict_types=1);

namespace Builder\Behaviors;

use Psr\Container\ContainerInterface;
use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;

trait PdfFormatTestCaseTrait
{
    abstract protected function getBuilder(): BuilderInterface;

    abstract protected function getDependencies(): ContainerInterface;

    public function testPdfFormatSemanticConfigurationIsCorrectlySet(): void
    {

    }

    public function testPdfFormatIsCorrectlyNormalizedBeforeSend(): void
    {

    }
}
