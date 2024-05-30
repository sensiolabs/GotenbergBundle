<?php

declare(strict_types=1);

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Pdf;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use Sensiolabs\GotenbergBundle\Builder\Pdf\AbstractChromiumPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\AbstractPdfBuilder;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Sensiolabs\GotenbergBundle\Tests\Builder\AbstractBuilderTestCase;
use Symfony\Component\Filesystem\Filesystem;

#[CoversClass(AbstractChromiumPdfBuilder::class)]
#[UsesClass(AbstractPdfBuilder::class)]
class AbstractChromiumPdfBuilderTest extends AbstractBuilderTestCase
{
    protected static GotenbergClientInterface $gotenbergClient;
    protected static AssetBaseDirFormatter $assetBaseDirFormatter;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        self::$assetBaseDirFormatter = new AssetBaseDirFormatter(new Filesystem(), self::FIXTURE_DIR, self::FIXTURE_DIR);
    }

    protected function setUp(): void
    {
        self::$gotenbergClient = $this->createMock(GotenbergClientInterface::class);
    }

    public static function configurationIsCorrectlySetProvider(): \Generator
    {
        yield 'single_page' => ['single_page', false, [
            'singlePage' => 'false',
        ]];
        yield 'pdf_format - A-1b' => ['pdf_format', 'PDF/A-1b', [
            'pdfa' => 'PDF/A-1b',
        ]];
        yield 'pdf_universal_access' => ['pdf_universal_access', false, [
            'pdfua' => 'false',
        ]];
        yield 'paper_width - no units - string' => ['paper_width', '12', [
            'paperWidth' => '12in',
        ]];
        yield 'paper_width - no units - numeric' => ['paper_width', 12, [
            'paperWidth' => '12in',
        ]];
        yield 'paper_width - inches' => ['paper_width', '12in', [
            'paperWidth' => '12in',
        ]];
        yield 'paper_width - cm' => ['paper_width', '12cm', [
            'paperWidth' => '12cm',
        ]];
        yield 'paper_height' => ['paper_height', 10.0, [
            'paperHeight' => '10in',
        ]];
        yield 'margin_top' => ['margin_top', 10.0, [
            'marginTop' => '10in',
        ]];
        yield 'margin_bottom' => ['margin_bottom', 10.0, [
            'marginBottom' => '10in',
        ]];
        yield 'margin_left' => ['margin_left', 10.0, [
            'marginLeft' => '10in',
        ]];
        yield 'margin_right' => ['margin_right', 10.0, [
            'marginRight' => '10in',
        ]];
        yield 'prefer_css_page_size' => ['prefer_css_page_size', false, [
            'preferCssPageSize' => 'false',
        ]];
        yield 'print_background' => ['print_background', false, [
            'printBackground' => 'false',
        ]];
        yield 'omit_background' => ['omit_background', false, [
            'omitBackground' => 'false',
        ]];
        yield 'landscape' => ['landscape', false, [
            'landscape' => 'false',
        ]];
        yield 'scale' => ['scale', 2.0, [
            'scale' => '2.0',
        ]];
        yield 'native_page_ranges' => ['native_page_ranges', '1-10', [
            'nativePageRanges' => '1-10',
        ]];
        yield 'wait_delay' => ['wait_delay', '3ms', [
            'waitDelay' => '3ms',
        ]];
        yield 'wait_for_expression' => ['wait_for_expression', "window.status === 'ready'", [
            'waitForExpression' => "window.status === 'ready'",
        ]];
        yield 'emulated_media_type' => ['emulated_media_type', 'screen', [
            'emulatedMediaType' => 'screen',
        ]];
        yield 'cookies' => ['cookies', [['name' => 'MyCookie', 'value' => 'raspberry']], [
            'cookies' => '[{"name":"MyCookie","value":"raspberry"}]',
        ]];
        yield 'extra_http_headers' => ['extra_http_headers', ['MyHeader' => 'SomeValue'], [
            'extraHttpHeaders' => '{"MyHeader":"SomeValue"}',
        ]];
        yield 'fail_on_http_status_codes' => ['fail_on_http_status_codes', [499, 500], [
            'failOnHttpStatusCodes' => '[499,500]',
        ]];
        yield 'fail_on_console_exceptions' => ['fail_on_console_exceptions', false, [
            'failOnConsoleExceptions' => 'false',
        ]];
        yield 'skip_network_idle_event' => ['skip_network_idle_event', false, [
            'skipNetworkIdleEvent' => 'false',
        ]];
        yield 'metadata' => ['metadata', ['Author' => 'SensioLabs'], [
            'metadata' => '{"Author":"SensioLabs"}',
        ]];
    }

    /**
     * @param array<mixed> $expected
     */
    #[DataProvider('configurationIsCorrectlySetProvider')]
    public function testConfigurationIsCorrectlySet(string $key, mixed $value, array $expected): void
    {
        $builder = $this->getChromiumPdfBuilder();
        $builder->setConfigurations([
            $key => $value,
        ]);

        self::assertEquals($expected, $builder->getMultipartFormData()[0]);
    }

    private function getChromiumPdfBuilder(): AbstractChromiumPdfBuilder
    {
        return new class(self::$gotenbergClient, self::$assetBaseDirFormatter, self::$twig) extends AbstractChromiumPdfBuilder {
            protected function getEndpoint(): string
            {
                return '/fake/endpoint';
            }
        };
    }
}
