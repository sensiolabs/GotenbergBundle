<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Pdf;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use Sensiolabs\GotenbergBundle\Builder\GotenbergFileResult;
use Sensiolabs\GotenbergBundle\Builder\Pdf\AbstractPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\SplitPdfBuilder;
use Sensiolabs\GotenbergBundle\Enumeration\SplitMode;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Sensiolabs\GotenbergBundle\Processor\NullProcessor;
use Sensiolabs\GotenbergBundle\Tests\Builder\AbstractBuilderTestCase;

#[CoversClass(SplitPdfBuilder::class)]
#[UsesClass(AbstractPdfBuilder::class)]
#[UsesClass(AssetBaseDirFormatter::class)]
#[UsesClass(GotenbergFileResult::class)]
final class SplitPdfBuilderTest extends AbstractBuilderTestCase
{
    private const PDF_DOCUMENTS_DIR = 'pdf';

    public function testEndpointIsCorrect(): void
    {
        $this->gotenbergClient
            ->expects($this->once())
            ->method('call')
            ->with(
                $this->equalTo('/forms/pdfengines/split'),
                $this->anything(),
                $this->anything(),
            )
        ;

        $this->getSplitPdfBuilder()
            ->files(self::PDF_DOCUMENTS_DIR.'/multi_page.pdf')
            ->splitMode(SplitMode::Pages)
            ->splitSpan('1')
            ->generate()
        ;
    }

    public static function configurationIsCorrectlySetProvider(): \Generator
    {
        yield 'split_mode' => [
            [
                'split_mode' => 'pages',
                'split_span' => '1',
            ],
            [
                'splitMode' => 'pages',
            ],
        ];
        yield 'split_span' => [
            [
                'split_span' => '1',
                'split_mode' => 'pages',
            ],
            [
                'splitSpan' => '1',
            ],
        ];
        yield 'split_unify' => [
            [
                'split_unify' => true,
                'split_span' => '1',
                'split_mode' => 'pages',
            ],
            [
                'splitUnify' => true,
            ],
        ];
    }

    /**
     * @param array<mixed> $configurations
     * @param array<mixed> $expected
     */
    #[DataProvider('configurationIsCorrectlySetProvider')]
    public function testConfigurationIsCorrectlySet(array $configurations, array $expected): void
    {
        $builder = $this->getSplitPdfBuilder();
        $builder->setConfigurations($configurations);
        $builder->files(self::PDF_DOCUMENTS_DIR.'/multi_page.pdf');

        self::assertEquals($expected, $builder->getMultipartFormData()[0]);
    }

    public function testRequiredFormData(): void
    {
        $builder = $this->getSplitPdfBuilder();

        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('"splitMode" and "splitSpan" must be provided.');

        $builder->getMultipartFormData();
    }

    public function testRequiredFile(): void
    {
        $builder = $this->getSplitPdfBuilder()
            ->splitMode(SplitMode::Pages)
            ->splitSpan('1')
        ;

        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('At least one PDF file is required');

        $builder->getMultipartFormData();
    }

    private function getSplitPdfBuilder(): SplitPdfBuilder
    {
        return (new SplitPdfBuilder($this->gotenbergClient, self::$assetBaseDirFormatter, $this->webhookConfigurationRegistry))
            ->processor(new NullProcessor())
        ;
    }
}
