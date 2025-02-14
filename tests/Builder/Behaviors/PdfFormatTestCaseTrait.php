<?php

declare(strict_types=1);

namespace Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;

trait PdfFormatTestCaseTrait
{
    abstract protected function getBuilder(): BuilderInterface;

    public function testPdfFormatSemanticConfigurationIsCorrectlySet(): void
    {

    }

    public function testPdfFormatIsCorrectlyNormalizedBeforeSend(): void
    {

    }
}
