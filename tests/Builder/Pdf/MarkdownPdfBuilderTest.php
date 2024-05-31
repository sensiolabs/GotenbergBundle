<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Pdf;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use Sensiolabs\GotenbergBundle\Builder\Pdf\AbstractChromiumPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\AbstractPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\MarkdownPdfBuilder;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Sensiolabs\GotenbergBundle\Tests\Builder\AbstractBuilderTestCase;

#[CoversClass(MarkdownPdfBuilder::class)]
#[UsesClass(AbstractChromiumPdfBuilder::class)]
#[UsesClass(AbstractPdfBuilder::class)]
#[UsesClass(AssetBaseDirFormatter::class)]
final class MarkdownPdfBuilderTest extends AbstractBuilderTestCase
{
    public function testEndpointIsCorrect(): void
    {
        $this->gotenbergClient
            ->expects($this->once())
            ->method('call')
            ->with(
                $this->equalTo('/forms/chromium/convert/markdown'),
                $this->anything(),
                $this->anything(),
            )
        ;
        $builder = $this->getMarkdownPdfBuilder();
        $builder
            ->wrapperFile('files/wrapper.html')
            ->files('assets/file.md')
        ;
        $builder->generate();
    }

    public function testRequiredWrapperTemplate(): void
    {
        $builder = $this->getMarkdownPdfBuilder();
        $builder
            ->files('assets/file.md')
        ;

        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('HTML template is required');

        $builder->getMultipartFormData();
    }

    public function testRequiredMarkdownFile(): void
    {
        $builder = $this->getMarkdownPdfBuilder();
        $builder
            ->wrapperFile('files/wrapper.html')
        ;

        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('At least one markdown file is required');

        $builder->getMultipartFormData();
    }

    private function getMarkdownPdfBuilder(bool $twig = true): MarkdownPdfBuilder
    {
        return new MarkdownPdfBuilder($this->gotenbergClient, self::$assetBaseDirFormatter, true === $twig ? self::$twig : null);
    }
}
