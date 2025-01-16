<?php

namespace Sensiolabs\GotenbergBundle\Configurator;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Pdf\HtmlPdfBuilder;
use Sensiolabs\GotenbergBundle\Enumeration\EmulatedMediaType;
use Sensiolabs\GotenbergBundle\Enumeration\PaperSize;
use Sensiolabs\GotenbergBundle\Enumeration\PdfFormat;
use Sensiolabs\GotenbergBundle\Enumeration\SplitMode;
use Sensiolabs\GotenbergBundle\Enumeration\Unit;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;

/**
 * @extends AbstractBuilderConfigurator<HtmlPdfBuilder>
 */
class HtmlPdfBuilderConfigurator extends AbstractBuilderConfigurator
{
    protected function configure(BuilderInterface $builder, string $name, mixed $value): void
    {
        match ($name) {
            'header' => $builder->header(...$value),
            'footer' => $builder->footer(...$value),
            'single_page' => $builder->singlePage($value),
            'pdf_format' => $builder->pdfFormat(PdfFormat::from($value)),
            'pdf_universal_access' => $builder->pdfUniversalAccess($value),
            'paper_standard_size' => $builder->paperStandardSize(PaperSize::from($value)),
            'paper_width' => $builder->paperWidth(...Unit::parse($value)),
            'paper_height' => $builder->paperHeight(...Unit::parse($value)),
            'margin_top' => $builder->marginTop(...Unit::parse($value)),
            'margin_bottom' => $builder->marginBottom(...Unit::parse($value)),
            'margin_left' => $builder->marginLeft(...Unit::parse($value)),
            'margin_right' => $builder->marginRight(...Unit::parse($value)),
            'prefer_css_page_size' => $builder->preferCssPageSize($value),
            'generate_document_outline' => $builder->generateDocumentOutline($value),
            'print_background' => $builder->printBackground($value),
            'omit_background' => $builder->omitBackground($value),
            'landscape' => $builder->landscape($value),
            'scale' => $builder->scale($value),
            'native_page_ranges' => $builder->nativePageRanges($value),
            'wait_delay' => $builder->waitDelay($value),
            'wait_for_expression' => $builder->waitForExpression($value),
            'emulated_media_type' => $builder->emulatedMediaType(EmulatedMediaType::from($value)),
            'cookies' => $builder->cookies($value),
            'user_agent' => $builder->userAgent($value),
            'extra_http_headers' => $builder->extraHttpHeaders($value),
            'fail_on_http_status_codes' => $builder->failOnHttpStatusCodes($value),
            'fail_on_resource_http_status_codes' => $builder->failOnResourceHttpStatusCodes($value),
            'fail_on_resource_loading_failed' => $builder->failOnResourceLoadingFailed($value),
            'fail_on_console_exceptions' => $builder->failOnConsoleExceptions($value),
            'skip_network_idle_event' => $builder->skipNetworkIdleEvent($value),
            'metadata' => $builder->metadata($value),
            'download_from' => $builder->downloadFrom($value),
            'split_mode' => $builder->splitMode(SplitMode::from($value)),
            'split_span' => $builder->splitSpan($value),
            'split_unify' => $builder->splitUnify($value),
            default => throw new InvalidBuilderConfiguration(\sprintf('Invalid option "%s": no method does not exist in class "%s" to configured it.', $name, $builder::class)),
        };
    }
}
