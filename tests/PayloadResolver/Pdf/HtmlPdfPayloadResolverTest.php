<?php

namespace Sensiolabs\GotenbergBundle\Tests\PayloadResolver\Pdf;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Enumeration\EmulatedMediaType;
use Sensiolabs\GotenbergBundle\Enumeration\PdfFormat;
use Sensiolabs\GotenbergBundle\Enumeration\SplitMode;
use Sensiolabs\GotenbergBundle\PayloadResolver\PayloadResolverInterface;
use Sensiolabs\GotenbergBundle\PayloadResolver\Pdf\HtmlPdfPayloadResolver;
use Sensiolabs\GotenbergBundle\Tests\PayloadResolver\AbstractPayloadResolverTestCase;

#[CoversClass(HtmlPdfPayloadResolver::class)]
final class HtmlPdfPayloadResolverTest extends AbstractPayloadResolverTestCase
{
    public static function provideBodyBagParam(): iterable
    {
        yield 'paper_width' => [
            'paperWidth',
            '8.27',
            '8.27in',
        ];
        yield 'paper_height' => [
            'paperWidth',
            '11.7',
            '11.7in',
        ];
        yield 'margin_top' => [
            'marginTop',
            '1cm',
            '1cm',
        ];
        yield 'margin_bottom' => [
            'marginBottom',
            '1in',
            '1in',
        ];
        yield 'margin_left' => [
            'marginLeft',
            '1in',
            '1in',
        ];
        yield 'margin_right' => [
            'marginRight',
            '1in',
            '1in',
        ];
        yield 'prefer_css_page_size' => [
            'preferCssPageSize',
            true,
            'true',
        ];
        yield 'generate_document_outline' => [
            'generateDocumentOutline',
            true,
            'true',
        ];
        yield 'print_background' => [
            'printBackground',
            true,
            'true',
        ];
        yield 'omit_background' => [
            'omitBackground',
            true,
            'true',
        ];
        yield 'landscape' => [
            'landscape',
            true,
            'true',
        ];
        yield 'scale' => [
            'scale',
            1.5,
            '1.5',
        ];
        yield 'native_page_ranges' => [
            'nativePageRanges',
            '1-5',
            '1-5',
        ];
        yield 'wait_delay' => [
            'waitDelay',
            '10s',
            '10s',
        ];
        yield 'wait_for_expression' => [
            'waitForExpression',
            'window.globalVar === "ready"',
            'window.globalVar === "ready"',
        ];
        yield 'emulated_media_type' => [
            'emulatedMediaType',
            EmulatedMediaType::Screen,
            'screen',
        ];
        yield 'cookies' => [
            'cookies',
            [
                'cook_me' => [
                    'name' => 'cook_me',
                    'value' => 'sensio',
                    'domain' => 'sensiolabs.com',
                    'secure' => true,
                    'httpOnly' => true,
                    'sameSite' => 'Lax',
                    'path' => null,
                ],
            ],
            '[{"name":"cook_me","value":"sensio","domain":"sensiolabs.com","secure":true,"httpOnly":true,"sameSite":"Lax","path":null}]',
        ];
        yield 'extra_http_headers' => [
            'extraHttpHeaders',
            ['MyHeader' => 'MyValue', 'User-Agent' => 'MyValue'],
            '{"MyHeader":"MyValue","User-Agent":"MyValue"}',
        ];
        yield 'fail_on_http_status_codes' => [
            'failOnHttpStatusCodes',
            [401],
            '[401]',
        ];
        yield 'fail_on_resource_http_status_codes' => [
            'failOnResourceHttpStatusCodes',
            [401],
            '[401]',
        ];
        yield 'fail_on_resource_loading_failed' => [
            'failOnResourceLoadingFailed',
            true,
            'true',
        ];
        yield 'fail_on_console_exceptions' => [
            'failOnConsoleExceptions',
            true,
            'true',
        ];
        yield 'skip_network_idle_event' => [
            'skipNetworkIdleEvent',
            true,
            'true',
        ];
        yield 'pdf_format' => [
            'pdfa',
            PdfFormat::Pdf1b,
            'PDF/A-1b',
        ];
        yield 'pdf_universal_access' => [
            'pdfua',
            true,
            'true',
        ];

        yield 'split_mode' => [
            'splitMode',
            SplitMode::Pages,
            'pages',
        ];
        yield 'split_span' => [
            'splitSpan',
            '1-5',
            '1-5',
        ];
        yield 'split_unify' => [
            'splitUnify',
            true,
            'true',
        ];
    }

    #[DataProvider('provideBodyBagParam')]
    public function testResolveBody(string $name, mixed $value, mixed $expected): void
    {
        $bodyBag = new BodyBag();
        $bodyBag
            ->set('index.html', $this->createMock(\SplFileInfo::class))
            ->set($name, $value)
        ;

        $resolvedData = $this->getResolver()->resolveBody($bodyBag);
        self::assertSame($expected, $resolvedData[$name]);
    }

    protected function createResolver(string $gotenbergApiVersion): PayloadResolverInterface
    {
        return new HtmlPdfPayloadResolver(self::GOTENBERG_API_VERSION);
    }
}
