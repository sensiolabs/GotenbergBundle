<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Pdf;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use Sensiolabs\GotenbergBundle\Builder\Pdf\AbstractChromiumPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\AbstractPdfBuilder;
use Sensiolabs\GotenbergBundle\Enumeration\PaperSizeInterface;
use Sensiolabs\GotenbergBundle\Enumeration\PdfFormat;
use Sensiolabs\GotenbergBundle\Enumeration\Unit;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Sensiolabs\GotenbergBundle\Exception\PdfPartRenderingException;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Sensiolabs\GotenbergBundle\Tests\Builder\AbstractBuilderTestCase;
use Sensiolabs\GotenbergBundle\Twig\GotenbergAssetExtension;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

#[CoversClass(AbstractChromiumPdfBuilder::class)]
#[UsesClass(AbstractPdfBuilder::class)]
#[UsesClass(Unit::class)]
#[UsesClass(AssetBaseDirFormatter::class)]
#[UsesClass(GotenbergAssetExtension::class)]
class AbstractChromiumPdfBuilderTest extends AbstractBuilderTestCase
{
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
        yield 'paper_width' => ['paper_width', 10.0, [
            'paperWidth' => '10in',
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
        yield 'user_agent' => ['user_agent', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko)', [
            'userAgent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko)',
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
    #[TestDox('Configuration "$_dataName" is correctly set')]
    public function testConfigurationIsCorrectlySet(string $key, mixed $value, array $expected): void
    {
        $builder = $this->getChromiumPdfBuilder();
        $builder->setConfigurations([
            $key => $value,
        ]);

        self::assertEquals($expected, $builder->getMultipartFormData()[0]);
    }

    public function testConfigurationNotFoundThrowError(): void
    {
        $builder = $this->getChromiumPdfBuilder();

        $this->expectException(InvalidBuilderConfiguration::class);

        $builder->setConfigurations([
            'fake' => 'value',
        ]);
    }

    public function testPaperSizeAppliesWidthAndHeight(): void
    {
        $builder = $this->getChromiumPdfBuilder();
        $builder->paperSize(10.0, 50.5, Unit::Centimeters);

        self::assertEquals([
            ['paperWidth' => '10cm'],
            ['paperHeight' => '50.5cm'],
        ], $builder->getMultipartFormData());
    }

    public function testPaperStandardSizeAppliesCorrectly(): void
    {
        $paperStandardSize = new class implements PaperSizeInterface {
            public function width(): float
            {
                return 10.0;
            }

            public function height(): float
            {
                return 50.5;
            }

            public function unit(): Unit
            {
                return Unit::Pixels;
            }
        };

        $builder = $this->getChromiumPdfBuilder();
        $builder->paperStandardSize($paperStandardSize);

        self::assertEquals([
            ['paperWidth' => '10px'],
            ['paperHeight' => '50.5px'],
        ], $builder->getMultipartFormData());
    }

    public function testMarginsAppliesCorrectly(): void
    {
        $builder = $this->getChromiumPdfBuilder();
        $builder->margins(1.1, 2.2, 3.3, 4.4, Unit::Picas);

        self::assertEquals([
            ['marginTop' => '1.1pc'],
            ['marginBottom' => '2.2pc'],
            ['marginLeft' => '3.3pc'],
            ['marginRight' => '4.4pc'],
        ], $builder->getMultipartFormData());
    }

    public function testPdfFormatCanBeReset(): void
    {
        $builder = $this->getChromiumPdfBuilder();
        $builder->pdfFormat(PdfFormat::Pdf1b);

        self::assertEquals([
            ['pdfa' => 'PDF/A-1b'],
        ], $builder->getMultipartFormData());

        $builder->pdfFormat(null);

        self::assertEquals([], $builder->getMultipartFormData());
    }

    public function testTwigHeaderIsCorrectlyRendered(): void
    {
        $builder = $this->getChromiumPdfBuilder();
        $builder->header('templates/header.html.twig', ['name' => 'World']);

        $data = $builder->getMultipartFormData()[0];

        $expected = <<<HTML
        <!DOCTYPE html>
        <html lang="en">
            <head>
                <meta charset="utf-8" />
                <title>My Header</title>
            </head>
            <body>
                <h1>Hello World!</h1>
            </body>
        </html>

        HTML;

        self::assertFile($data, 'header.html', expectedContent: $expected);
    }

    public function testTwigFooterIsCorrectlyRendered(): void
    {
        $builder = $this->getChromiumPdfBuilder();
        $builder->footer('templates/footer.html.twig', ['name' => 'World']);

        $data = $builder->getMultipartFormData()[0];

        $expected = <<<HTML
        <!DOCTYPE html>
        <html lang="en">
            <head>
                <meta charset="utf-8" />
                <title>My Footer</title>
            </head>
            <body>
                <h1>Hello World!</h1>
            </body>
        </html>

        HTML;

        self::assertFile($data, 'footer.html', expectedContent: $expected);
    }

    public function testPlainFileHeaderIsCorrectlyRendered(): void
    {
        $builder = $this->getChromiumPdfBuilder();
        $builder->headerFile('files/header.html');

        $data = $builder->getMultipartFormData()[0];

        $expected = <<<HTML
        <!DOCTYPE html>
        <html lang="en">
            <head>
                <meta charset="utf-8" />
                <title>My Header</title>
            </head>
            <body>
                <h1>Hello Header</h1>
            </body>
        </html>

        HTML;

        self::assertFile($data, 'header.html', expectedContent: $expected);
    }

    public function testPlainFileFooterIsCorrectlyRendered(): void
    {
        $builder = $this->getChromiumPdfBuilder();
        $builder->footerFile('files/footer.html');

        $data = $builder->getMultipartFormData()[0];

        $expected = <<<HTML
        <!DOCTYPE html>
        <html lang="en">
            <head>
                <meta charset="utf-8" />
                <title>My Footer</title>
            </head>
            <body>
                <h1>Hello Footer</h1>
            </body>
        </html>

        HTML;

        self::assertFile($data, 'footer.html', expectedContent: $expected);
    }

    public function testAssetsCanBeAddedUsingPhp(): void
    {
        $builder = $this->getChromiumPdfBuilder();
        $builder->assets(
            self::FIXTURE_DIR.'/assets/logo.png',
            self::FIXTURE_DIR.'/assets/logo.png',
            self::FIXTURE_DIR.'/assets/other_logo.png',
        );

        $data = $builder->getMultipartFormData();

        self::assertCount(2, $data);

        $logo = $data[0];
        self::assertFile($logo, 'logo.png', 'image/png');

        $otherLogo = $data[1];
        self::assertFile($otherLogo, 'other_logo.png', 'image/png');
    }

    public function testAssetsCanBeAddedUsingTwig(): void
    {
        $builder = $this->getChromiumPdfBuilder();
        $builder->header('templates/header_with_asset.html.twig', ['name' => 'World']);

        $data = $builder->getMultipartFormData();

        self::assertCount(2, $data);

        $logo = $data[0];
        self::assertFile($logo, 'logo.png', 'image/png');
    }

    public function testCanAddCookies(): void
    {
        $builder = $this->getChromiumPdfBuilder();
        $builder->addCookies([
            [
                'name' => 'MyCookie',
                'value' => 'Chocolate',
                'domain' => 'sensiolabs.com',
            ],
            [
                'name' => 'MyCookie',
                'value' => 'Vanilla',
                'domain' => 'sensiolabs.com',
            ],
        ]);

        $data = $builder->getMultipartFormData();

        self::assertEquals([
            'cookies' => '[{"name":"MyCookie","value":"Vanilla","domain":"sensiolabs.com"}]',
        ], $data[0]);
    }

    public function testCanForwardCookies(): void
    {
        $request = new Request();
        $request->headers->set('Host', 'sensiolabs.com');
        $request->cookies->set('MyCookie', 'Chocolate');

        $requestStack = new RequestStack();
        $requestStack->push($request);

        $builder = $this->getChromiumPdfBuilder(requestStack: $requestStack);
        $builder->forwardCookie('MyCookie');

        $data = $builder->getMultipartFormData();

        self::assertEquals([
            'cookies' => '[{"name":"MyCookie","value":"Chocolate","domain":"sensiolabs.com"}]',
        ], $data[0]);
    }

    public function testCanAddExtraHttpHeaders(): void
    {
        $builder = $this->getChromiumPdfBuilder();
        $builder->addExtraHttpHeaders([
            'MyHeader' => 'SomeValue',
        ]);
        $builder->addExtraHttpHeaders([
            'MyHeader' => 'SomeOtherValue',
        ]);

        $data = $builder->getMultipartFormData();

        self::assertEquals([
            'extraHttpHeaders' => '{"MyHeader":"SomeOtherValue"}',
        ], $data[0]);
    }

    public function testAddExtraHttpHeadersDoesNothingIfEmpty(): void
    {
        $builder = $this->getChromiumPdfBuilder();

        $data = $builder->getMultipartFormData();
        $dataCount = \count($data);

        $builder->addExtraHttpHeaders([]);
        self::assertCount(max($dataCount - 1, 0), $builder->getMultipartFormData());
    }

    public function testCanResetExtraHttpHeaders(): void
    {
        $builder = $this->getChromiumPdfBuilder();
        $builder->addExtraHttpHeaders([
            'MyHeader' => 'SomeValue',
        ]);

        $data = $builder->getMultipartFormData();

        $dataCount = \count($data);

        $builder->extraHttpHeaders([]);

        $data = $builder->getMultipartFormData();
        self::assertCount($dataCount - 1, $data);
    }

    public function testCanAddMetadata(): void
    {
        $builder = $this->getChromiumPdfBuilder();
        $builder->addMetadata('Author', 'Me');
        $builder->addMetadata('Author', 'SensioLabs');

        $data = $builder->getMultipartFormData();

        self::assertEquals([
            'metadata' => '{"Author":"SensioLabs"}',
        ], $data[0]);
    }

    public function testThrowIfTwigNotAvailable(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Twig is required to use "Sensiolabs\GotenbergBundle\Builder\Pdf\AbstractChromiumPdfBuilder::withRenderedPart" method. Try to run "composer require symfony/twig-bundle".');

        $builder = $this->getChromiumPdfBuilder(false);
        $builder->header('header.html.twig', ['name' => 'World']);
    }

    public function testThrowIfTwigTemplateIsInvalid(): void
    {
        $this->expectException(PdfPartRenderingException::class);
        $this->expectExceptionMessage('Could not render template "templates/invalid.html.twig" into PDF part "header.html". Unexpected character "!".');

        $builder = $this->getChromiumPdfBuilder();
        $builder->header('templates/invalid.html.twig');
    }

    private function getChromiumPdfBuilder(bool $twig = true, RequestStack $requestStack = new RequestStack()): AbstractChromiumPdfBuilder
    {
        return new class($this->gotenbergClient, self::$assetBaseDirFormatter, $requestStack, true === $twig ? self::$twig : null) extends AbstractChromiumPdfBuilder {
            protected function getEndpoint(): string
            {
                return '/fake/endpoint';
            }
        };
    }
}
